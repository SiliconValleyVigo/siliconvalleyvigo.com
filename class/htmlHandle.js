
/**
 * handle html list class by id
 */
var HtmlHandle = {

    /**
     * Get the position id in document element by Class Name array
     * @param {Array} arr Array of elements
     * @param {String} id Id of element
     * @returns Position id in document element by Class Name array
     */
    positionClassById(arr, id) {
        let count = 0;
        let position = 0;
        for (let element of arr) {
            count++;
            if (element.id == id) {
                position = count;
            }
        }
        return position - 1;
    },

    /**
     * add a class to the elements of a list of ids
     * @param {Array} arr  Array of elements
     * @param {String} className Class name to add
     */
    addClassToIdList(arr, className) {
        for (let id in arr) {
            //get element
            let element = document.getElementById(arr[id]);

            //add class to element
            element.classList.add(className);
        }
    },

    /**
     * remove a class to the elements of a list of ids
     * @param {Array} arr  Array of elements
     * @param {String} className Class name to remove
     */
    removeClassToIdList(arr, className) {
        for (let id in arr) {

            //get element
            let element = document.getElementById(arr[id]);

            //remove class to element
            element.classList.remove(className);
        }
    },

    /**
     * Insert html file's contain in a html id
     * @param {string} url // Url location to html file in proyect
     * @param {string} id  // id whit insert the contain to html file
     */
    htmlInsert(url, id) {
        fetch(url)
          .then(response => response.text())
          .then(html => {
            const element = document.getElementById(id);
            if (element) {
              element.innerHTML = html;
            }
          })
          .catch(error => console.log('Error al insertar HTML:', error));
    },

    /**
     * go to the beginning of the html document
     */
    goToStart() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    /**
     * change a pathname to url for navigation
     * @param {string} newPathName //new path name to change
     */
    updateUrl(newPathName) {
        let url = new URL(window.location.href);
        url.pathname = newPathName;
        let newUrl = url.href;
      
        window.history.pushState(null, null, newUrl);
    }

}