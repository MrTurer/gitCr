class rnsHintsView {
  #currentPageUrl = '';
  #guides = [];
  #hintsPerPage = [];
  #currentGuide = null;
  #manager = null;

  constructor() {
    this.#currentPageUrl = window.location.href.split('?')[0];
    this.#manager = BX.UI.Tour.Manager;
    this.#hintsPerPage = getHintsGeneralListFromStorage(this.#currentPageUrl);
  }

  getElementBySelector = (selector) => {
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
    for( let child of selector.children ){
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

  render = () => {
    if ( this.#hintsPerPage.length > 0 ) {
      this.#hintsPerPage.forEach((item, index) => {

        if (item.ACTIVE) {
          if( item.TYPE === 'group' ){
            if( item.HINTS.length > 0 ){
              let steps = [];

              for(let hint of item.HINTS){
                const hintElement = this.getElementBySelector(hint.HINT_ELEMENT);

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

              this.#guides.push(
                this.#manager.create({
                  id: 'id-hint-tour-' + item.ID,
                  events: {
                    onFinish: () => {
                      if( this.#guides.length > 0 ){
                        setTimeout(() => {
                          this.#currentGuide = this.#guides.shift();
                          this.#currentGuide.start();
                        }, 300)
                      }
                    }
                  },
                  steps: steps,
                })
              );
            }
          } else {

            const hintElement = this.getElementBySelector(item.HINT_ELEMENT);

            this.#guides.push(this.#manager.create({
              id: 'id-single-hint-tour-' + item.ID,
              events: {
                onStart: () => {
                  setTimeout(() => {
                    this.#currentGuide.getCurrentCounter().classList.add('hidden');
                    this.#currentGuide.getCounterItems().classList.add('hidden');
                  });
                },
                onFinish: () => {
                  if( this.#guides.length > 0 ){
                    setTimeout(() => {
                      this.#currentGuide = this.#guides.shift();
                      this.#currentGuide.start();
                    }, 300)
                  }
                }
              },
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
            }));
          }
        }
      });

      if( this.#guides.length > 0 ){
        this.#currentGuide = this.#guides.shift();
        this.#currentGuide.start();
      }
    }
  }
}


