
## API's

Cat photo API
```
function onClickSearch() {
            btnSearch.disabled = true;

            SearchApi = 'https://api.thecatapi.com/v1/images/search';
            console.log(SearchApi);
            fetch(SearchApi, {
                mode: 'cors'
            })
            .then(resp => resp.json())
            .then(jsonResp => {
                modal.style.display = "block";
                console.log(jsonResp);
                modalImg.src = jsonResp[0].url;
                captionText.innerHTML = "IT'S A CAT !";
                
                btnSearch.disabled = false;
            })
            .catch(err => {
                alert(err);
                btnSearch.disabled = false;
            });
        }
```

