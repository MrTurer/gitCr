BX.ready(function () {
  BX.bind(document, "readystatechange", function () {
    const currentPageUrl = window.location.href.split('?')[0];

    let hintsPerPage = getHintsGeneralList();

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
                const hintElement =
                  document.querySelector(`.${hint.HINT_ELEMENT.split('.').join('-')}`) === null
                    ? document.getElementById(`${hint.HINT_ELEMENT}`)
                    : document.querySelector(`.${hint.HINT_ELEMENT.split('.').join('-')}`);

                steps.push({
                  target: hintElement,
                  id: hint.HINT_ELEMENT.split('.').join('-'),
                  text: hint.DETAIL_TEXT,
                  areaPadding: 0,
                  link: "",
                  rounded: false,
                  title: hint.NAME,
                  position: null
                })
              }

              BX.UI.Tour.Manager.add({
                id: 'id-hint-tour-' + item.ID,
                steps: steps
              });

            }
          } else {

            const hintElement =
              document.querySelector(`.${item.HINT_ELEMENT.split('.').join('-')}`) === null
                ? document.getElementById(`${item.HINT_ELEMENT}`)
                : document.querySelector(`.${item.HINT_ELEMENT.split('.').join('-')}`);

            BX.UI.Tour.Manager.add({
              id: 'hintTour',
              steps: [{
                target: hintElement,
                id: item.HINT_ELEMENT.split('.').join('-'),
                text: item.DETAIL_TEXT,
                areaPadding: 0,
                link: "",
                rounded: false,
                title: item.NAME,
                position: null
              }]
            });
          }
        }
      });
    }
  });
});
