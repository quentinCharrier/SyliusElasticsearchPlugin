const inputField = document.querySelector('#bitbag_elasticsearch_search_box_query');
const responseField = document.querySelector('#search_autocomplete');
const productResponseField = document.querySelector('#product-search-autocomplete');
const categoryResponseField = document.querySelector('#category-search-autocomplete');
const blogResponseField = document.querySelector('#blog-search-autocomplete');

inputField.addEventListener('input', autocompleteSearch);
inputField.addEventListener('focusout', () => {setTimeout(function(){responseField.style.display = 'none';},500);});
inputField.addEventListener('focusin', autocompleteSearch);

async function autocompleteSearch () {

    let query = inputField.value;

    if (query === '') {
        return null;
    }

    const response = await fetch(`/auto-complete/product?query=${query}`);
    document.querySelector('#bitbag_elasticsearch_search_box_search').classList.add("loading");

    if (response.ok) {
        const responseJson = await response.json();

        if (responseJson.items.length > 0 ) {
            renderProduct(responseJson.items);
        }

        renderCategory(responseJson.categories);
        renderArticles(responseJson.articles);
        
        if (responseJson.articles.length === 0 && responseJson.categories.length === 0 && responseJson.items.length === 0 )
        {
            renderEmpty();
        }

        setTimeout(function(){document.querySelector('#bitbag_elasticsearch_search_box_search').classList.remove("loading");},500); 
        
        responseField.style.display = 'block';

    }
}

function renderEmpty ()
{
    productResponseField.innerHTML = '<p>No result for your search</p>';
}

function renderProduct (items)
{
    let resultProduct = [];

    items.forEach(function (item, index) {

          const maxResults = 10;
          const taxonName = item.taxon_name;

          if (index >= maxResults) {
            return false;
          }

          resultProduct.push(item);
    });

    const htmlProduct = resultProduct.map( product => {
        return `
        <div class="column">
            <div class="ui fluid">
                <a href="/products/${product.slug}">
                    <img src="${product.image}" alt="${product.name}" class="ui bordered image">
                    <div class="content">
                        ${product.name}
                        <div class="sylius-product-price">${product.price}</div>
                    </div>
                </a>
            </div>
        </div>`; 
    }).join('');

    productResponseField.innerHTML = htmlProduct;
}

function renderCategory (categories)
{
    if (categories.length === 0) {
        categoryResponseField.innerHTML = '';
        return;
    }

    let resultCategory = [];

    categories.forEach(function (item, index) {

          const maxResults = 3;

          if (index >= maxResults) {
            return false;
          }

          resultCategory.push(item);
    });

    const htmlCategory = resultCategory.map( category => {
        return `
            <div class="ui fluid">
                <a href="${category.slug}">
                    <div class="content">
                        ${category.name}
                    </div>
                </a>
            </div>`; 
    }).join('');

    const title = '<span>Category<span>';

    categoryResponseField.innerHTML = title + htmlCategory;
}

function renderArticles (articles)
{
    if (articles.length === 0) {
        blogResponseField.innerHTML = '';
        return;
    }

    let resultArticle = [];

    articles.forEach(function (item, index) {

          const maxResults = 3;

          if (index >= maxResults) {
            return false;
          }

          resultArticle.push(item);
    });

    const htmlBlog = resultArticle.map( article => {
        return `
            <div class="ui fluid">
                <a href="${article.slug}">
                    <img src="${article.image}" alt="${article.name}" class="ui bordered image">
                    <div class="content">
                        ${article.name}
                    </div>
                </a>
            </div>`; 
    }).join('');

    const title = '<span>Articles<span>';

    blogResponseField.innerHTML = title + htmlBlog;
}