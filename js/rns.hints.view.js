BX.ready(function () {
  BX.bind(document, "readystatechange", function () {
    const currentPageUrl = window.location.href.split('?')[0];

    const getElementBySelector = (selector) => {
      if( !selector || !selector.selector ){
        return null;
      }

      if( selector.children.length === 0 ){
        return {
          element: document.querySelector(selector.selector),
          id: selector.selector
        };
      }

      let element = document.querySelector(selector.selector);
      let id = selector.selector;
      for( child of selector.children ){
        if( element.children[child.sibling] ) {
          element = element.children[child.sibling];
          id += '-' + child.sibling;
        }
      }

      return {
        element,
        id
      };
    }

    let hintsPerPage = getHintsGeneralListFromStorage();

    if (
      hintsPerPage !== null &&
      hintsPerPage.length &&
      hintsPerPage[0].CURRENT_PAGE_URL === currentPageUrl
    ) {
      hintsPerPage.forEach((item, index) => {

        if (item.ACTIVE) {
          if( item.TYPE === 'group' ){
            if( item.HINTS.length > 0 ){
              let steps = [];

              for(let hint of item.HINTS){
                const hintElement = getElementBySelector(hint.HINT_ELEMENT);

                steps.push({
                  target: hintElement.element,
                  id: hintElement.id,
                  text: hint.DETAIL_TEXT,
                  areaPadding: 0,
                  link: "",
                  rounded: false,
                  title: hint.NAME,
                  position: 'right',
                })
              }

              BX.UI.Tour.Manager.add({
                id: 'id-hint-tour-' + item.ID,
                steps: steps
              });

            }
          } else {

            const hintElement = getElementBySelector(item.HINT_ELEMENT);

            BX.UI.Tour.Manager.add({
              id: 'id-single-hint-tour-' + item.ID,
              simpleMode: true,
              steps: [{
                target: hintElement.element,
                id: hintElement.id,
                text: item.DETAIL_TEXT,
                areaPadding: 0,
                link: "",
                rounded: false,
                title: item.NAME,
                position: 'right',
              }]
            });
          }
        }
      });
    }

    setTimeout(() => {
      document.querySelector('body').classList.remove('ui-tour-body-overflow');
    });
  });
});
