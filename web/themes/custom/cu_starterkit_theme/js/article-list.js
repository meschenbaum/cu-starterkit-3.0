/**  
 * Get additional data
 * @param {string} jsonurl - json api url
 */ 
async function getJsonData(jsonurl) {
  let response = await fetch(jsonurl);
  if (response.status == 200) {
    return response.json();
  } else {
    throw new Error(response);
  }
}

/**  
 * Get additional data from the taxonomy term attached to the Article node
 * @param {string} termurl - json:api url to get the specific term
 */ 
async function getTerm(termurl) {
  let term = [], myCall;
  try {
    myCall = await getJsonData(termurl);
    if (Array.isArray(myCall.data) && myCall.data.length) {
      if (typeof myCall.data[0].attributes.drupal_internal__tid === 'undefined') {
        term['tid'] =  '';
      } else {
        term['tid'] = myCall.data[0].attributes.drupal_internal__tid;
      }    
      if (typeof myCall.data[0].attributes.name === 'undefined') {
        term['name'] =  '';
      } else {
        term['name'] = myCall.data[0].attributes.name;
      }
    }
  } catch(error) {
    throw error;
  }
  return term;
}

/**  
 * Get additional data from the file attached to the Article node
 * @param {string} fileurl - json:api url to get the specific file
 */ 
async function getResponiveImage(fileurl) {
  let imageStyle = [], myCall;
  try {
    myCall = await getJsonData(fileurl);
    if (!(typeof myCall.data.attributes.image_style_uri === 'undefined')) {
      imageStyle = myCall.data.attributes.image_style_uri;
    }
  } catch(error) {
    throw error;
  }
  return imageStyle;
}

/**
 * Helper function to show/hide elements in the DOM
 * @param {string} id - CSS ID of the element to target
 * @param {string} display - display mode for that element (block | none) 
 */
function toggleMessage(id, display = "none") {
  if (id) {
    var toggle = document.getElementById(id);

    if (toggle) {
      switch (display) {
        case "block":
          toggle.style.display = "block";
          break;
        case "inline-block":
          toggle.style.display = "inline-block";
          break;
        case "none":
          toggle.style.display = "none";
          break;
        default:
          toggle.style.display = "none";
          break;
      }
    }
  }
}

/**
 * Main function that will load the initial data from the given URL and start processing it for display
 * @param {string} JSONURL - URL for the JSON:API endpoint with filters, sort and pagination 
 * @param {string} id - target DOM element to add the content to 
 * @param {string} ExcludeCategories - array of categories to filter out when rendering 
 * @param {string} ExcludeTags - array of tags to filter out when rendering
 * @param {string} DMPath = Pantheon Domain Masking SubPath
 * @returns - Promise with resolve or reject
 */
function renderArticleList( JSONURL, ExcludeCategories = "", ExcludeTags = "", DMPath = "") {
  return new Promise(function(resolve, reject) {
  let excludeCatArray = ExcludeCategories.split(",").map(Number);
  let excludeTagArray = ExcludeTags.split(",").map(Number);
  // next URL if there is one, will be returned by this funtion
  let NEXTJSONURL = "";

  if (JSONURL) {
    // show the loading spinner while we load the data
    toggleMessage("article-list-loading", "block");

    fetch(JSONURL)
      .then((reponse) => reponse.json())
      .then((data) => {
        // get the next URL and return that if there is one
        if(data.links.next) {
          let nextURL = data.links.next.href.split("/api/");
          NEXTJSONURL = nextURL[1];
        } else {
          NEXTJSONURL = "";
        }

        // if no articles of returned, stop the loading spinner and let the user know we received no data that matches their query
        if (!data.data.length) {
          toggleMessage("article-list-loading", "none");
          toggleMessage("article-list-no-results", "block");
          reject;
        }

        /**
         * Below objects are needed to match images with their corresponding articles.
         * There are two endpoints => data.data (article) and data.included (incl. media), both needed to associate a media library image with its respective article  
         */  
        let hasImg = false;
        let idObj = {};
        let altObj = {};
        let idObj2 = {};
        let altObj2 = {};
        // Remove any blanks from our articles before map
        if (data.included) {
          hasImg = true;          
          // removes all other included data besides images in our included media
          let idFilterData = data.included.filter((item) => {
            return item.type == "media--image";
          })

          let altFilterData = data.included.filter((item) => {
            return item.type == 'file--file';
          });
          // finds the file
          altFilterData.map((item)=>{
            altObj[item.id] = item.attributes.uri.url
            altObj2[item.id] = item.links.self.href
          })
          // using the image-only data, creates the idObj =>  key: thumbnail id, value : data id
          idFilterData.map((pair) => {
            idObj[pair.id] = pair.relationships.thumbnail.data.id;
            idObj2[pair.id] = pair.relationships.thumbnail.data.meta.alt;
          })
        }
        //iterate over each item in the array
        data.data.map((item) => {
          let thisArticleCats = [];
          let thisArticleTags = [];
          // // loop through and grab all of the categories
          if (item.relationships.field_categories) {
            for (let i = 0; i < item.relationships.field_categories.data.length; i++) {
              thisArticleCats.push(item.relationships.field_categories.data[i].meta.drupal_internal__target_id)
            }
          }

          // // loop through and grab all of the tags
          if (item.relationships.field_tags) {
            for (var i = 0; i < item.relationships.field_tags.data.length; i++) {
              thisArticleTags.push(item.relationships.field_tags.data[i].meta.drupal_internal__target_id)
            }
          }

          // checks to see if the current article (item) contains a category or tag scheduled for exclusion
          let doesIncludeCat = thisArticleCats;
          let doesIncludeTag = thisArticleTags;

          // check to see if we need to filter on categories
          if (excludeCatArray.length && thisArticleCats.length) {
            doesIncludeCat = thisArticleCats.filter((element) =>
              excludeCatArray.includes(element)
            )
          }
          // check to see if we need to filter on tags
          if (excludeTagArray.length && thisArticleTags.length) {
            doesIncludeTag = thisArticleTags.filter((element) =>
              excludeTagArray.includes(element)
            )
          }

          // if we didn't match any of the filtered tags or cats, then render the content
          if (doesIncludeCat.length == 0 && doesIncludeTag.length == 0) {
            // we need to render the Article Card view for this returned element
            // **ADD DATA**
            // If there is no summary then use the Meta Description as it trims the teaser.
            let body = '';
            if (!item.attributes.body.summary && !item.attributes.body.summary != '') {
              let tmpTeaser = "";
              if (item.attributes.metatag.length) {
                let metaFilterData = item.attributes.metatag.filter((item) => {
                  return item.attributes.name == "description";
                })
                metaFilterData.map((item)=>{
                  tmpTeaser = item.attributes.content
                })
              }
              body = (tmpTeaser !== null && tmpTeaser !== '') ? tmpTeaser : "";
            } else {
              body = item.attributes.body.summary;
            }
            body = body.trim();

            let date = '', hasDate = false;
            //Date - make human readable
            if (item.attributes.field_post_date !== null && item.attributes.field_post_date !== '') {
              const options = { year: 'numeric', month: 'short', day: 'numeric' };
              var tmpDate = item.attributes.field_post_date + ' 00:00:01';
              if (!isNaN(new Date(tmpDate).valueOf())) {
                date = new Date(tmpDate).toLocaleDateString('en-us', options);
                hasDate = true;
              }
            }

            let title = item.attributes.title;
            let link = DMPath.length ? DMPath + item.attributes.path.alias : item.attributes.path.alias;
            let articleSummarySize = "col-md-12";

            var articleRow = document.createElement('div')
            articleRow.className = 'card mb-3'
            articleRow.setAttribute('itemscope', '')
            articleRow.setAttribute('itemtype', 'https://schema.org/Article')

            var cardRow = document.createElement('div')
            cardRow.className = 'row g-0'

            if(link && hasImg && item.relationships.field_featured_media.data) {
              articleSummarySize = "col-md-10"

              var imgContainer = document.createElement('div')
              imgContainer.className = 'col-md-2 pt-3 px-3'

              var imgLink = document.createElement('a')
              imgLink.href = link;
              imgLink.setAttribute('title', title);
              //Get relative image styles for responsive view, fallback to thumbnail
              let thumbId = item.relationships.field_featured_media.data.id;
              
              getResponiveImage(altObj2[idObj[thumbId]])
              .then(function (result) {
                if (result) {
                  if (result.layout_default_article_list && result.layout_medium_article_list) {
                    var articlePicture = document.createElement('picture');
                    var pictureSrc = document.createElement('source');
                    pictureSrc.setAttribute('srcset', result.layout_medium_article_list);
                    pictureSrc.setAttribute('media', '(min-width: 768px)');
                    var pictureImg = document.createElement('img');
                    pictureImg.src = result.layout_default_article_list;
                    pictureImg.alt = title;
                    pictureImg.setAttribute('class', 'img-fluid');
                    pictureImg.setAttribute('loading', 'lazy');

                    articlePicture.appendChild(pictureSrc)
                    articlePicture.appendChild(pictureImg)
                    imgLink.appendChild(articlePicture)
                  }
                }
              })
              .catch(function(error) {
                throw error;
              });

              imgContainer.appendChild(imgLink)

              // add to card row
              cardRow.appendChild(imgContainer)
            }

            // Container
            var articleDataContainer = document.createElement('div')
            articleDataContainer.className = `col-sm-12 ${articleSummarySize}`
            
            // Body Container
            var articleBodyContainer = document.createElement('div')
            articleBodyContainer.className = `card-body`

            // Header
            var articleDataLink = document.createElement('a')
            articleDataLink.href = link;
            articleDataLink.innerText = title;

            var articleDataHead = document.createElement('h2')
            articleDataHead.className = "card-title"

            articleDataHead.appendChild(articleDataLink)

            // Date
            
            var articleCardDate = document.createElement('h6')
            articleCardDate.className = 'card-subtitle mb-2 text-body-secondary'
            articleCardDate.setAttribute('role', 'contentinfo')
            if (hasDate) {
              var articleCardDateCal = document.createElement('i')
              articleCardDateCal.className = 'fa-regular fa-calendar pe-1'
              var articleCardDateWrap = document.createElement('span')
              articleCardDateWrap.className = 'd-inline-block'
              articleCardDateWrap.setAttribute('itemprop', 'datePublished')
              articleCardDateWrap.innerText = date;
            }
            // ByLine
            let hasByline = false;
            if (item.relationships.field_byline) {
              var articleCardDateText = document.createTextNode(' by ')
              var articleCardDateByLine = document.createElement('span')
              articleCardDateByLine.className = 'd-inline-block'
              articleCardDateByLine.setAttribute('itemprop', 'author')

              getTerm(item.relationships.field_byline.links.related.href)
                .then(function (result) {
                  if (result && Array.isArray(result) && result.length) {
                    articleCardDateByLine.innerText = result.name;
                    hasByline = true;
                  }
                })
                .catch(function(error) {
                  throw error;
                });              
            }
            // Append
            if (hasDate) {
              articleCardDate.appendChild(articleCardDateCal)
              articleCardDate.appendChild(articleCardDateWrap)
            }
            if(hasByline) {
              articleCardDate.appendChild(articleCardDateText)
              articleCardDate.appendChild(articleCardDateByLine)
            }

            // Summary
            var articleSummaryBody = document.createElement('p')
            articleSummaryBody.className = 'card-text'
            articleSummaryBody.innerText = body;

            var readMoreLink = document.createElement('a')
            readMoreLink.className = 'card-link'
            readMoreLink.href = link;
            readMoreLink.innerText = `Read more`

            //Appends
            articleBodyContainer.appendChild(articleDataHead)
            if (hasDate && hasByline) {
              articleBodyContainer.appendChild(articleCardDate)
            }
            articleBodyContainer.appendChild(articleSummaryBody)
            articleBodyContainer.appendChild(readMoreLink)
            articleDataContainer.appendChild(articleBodyContainer)
            
            cardRow.appendChild(articleDataContainer)
            articleRow.appendChild(cardRow)

            let dataOutput = document.getElementById("article-list-data");
            let thisArticle = document.createElement("article");
            thisArticle.className = 'row mb-3 border-bottom border-primary';
            thisArticle.appendChild(articleRow);
            dataOutput.append(thisArticle);

            if(NEXTJSONURL){
              toggleMessage('article-list-load-more', 'inline-block')
            }
          }
        })

        // done loading -- hide the loading spinner graphic
        toggleMessage("article-list-loading", "none");
        resolve(NEXTJSONURL);
      }).catch(function(error) {
        // catch any fetch errors and let the user know so they're not endlessly watching the spinner
        console.log("Fetch Error in URL : " + JSONURL);
        console.log("Fetch Error is : " + error);
        // turn off spinner
        toggleMessage("article-list-loading", "none");
        // turn on default error message
        if(error){
          toggleMessage("article-list-error", "block");

        }

    });

  }
  });
}

/**
 * Initilization and start of code 
 */
(function () {
  // get the url from the data-jsonapi variable
  let el = document.getElementById("article-listing");
  let JSONURL = ""; // JSON:API URL 
  let NEXTJSONURL = ""; // next link for pagination 
  let CategoryExclude = ""; // categories to exclude
  let TagsExclude = ""; // tags to exclude 
  let DomainMaskingPath = ""; // Pantheon domain masking path

  // check to see if we have the data we need to work with.  
  if (el) {
    JSONURL = el.dataset.jsonapi;
    CategoryExclude = el.dataset.excats;
    TagsExclude = el.dataset.extags;
    DomainMaskingPath = el.dataset.dmpath;
  }
  
  // attempt to render the data requested 
  renderArticleList( JSONURL, CategoryExclude, TagsExclude, DomainMaskingPath,).then((response) => {
    if(response) {
      NEXTJSONURL = "/jsonapi/" + response;
    }
  });

  // watch for scrolling and determine if we're at the bottom of the content and need to request more
  const button = document.getElementById('article-list-load-more')
  button.addEventListener("click", function () {
          if(NEXTJSONURL) {
            renderArticleList( NEXTJSONURL, CategoryExclude, TagsExclude,).then((response) => {
              if(response) {
                NEXTJSONURL = "/jsonapi/" + response;
                loadingData = false;
              } else {
                NEXTJSONURL = "";
                toggleMessage("article-list-end-of-data", "block");
                toggleMessage('article-list-load-more')
              }
            });
          }
  })
})()
