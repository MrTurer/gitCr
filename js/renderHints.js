let renderHints;
BX.ready(function () {
  renderHints = () => {
    if (
      hintsPerPage !== null &&
      hintsPerPage.length &&
      hintsPerPage[0].CURRENT_PAGE_URL === currentPageUrl
    ) {
      let steps = [];
      hintsPerPage.forEach((hint, index) => {

        if (hint.ACTIVE) {
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

      });

      BX.UI.Tour.Manager.add({
        id: 'hintTour',
        steps: steps
      });

    }
  };
});
