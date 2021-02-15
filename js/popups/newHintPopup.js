/**
 * Форма добавления новой подсказки
 */
class newHintPopup {
  #popup = null;
  #currentPageUrl = '';
  #hintId = '';
  #hintNameFieldValue = '';
  #hintDescriptionFieldValue = '';
  #hintSerialFieldValue = 500;
  #hintGroupId = null;
  #hintElementSelector = null;

  #hintWindowTitle = '';
  #hintNameFieldLabel = '';
  #hintDescriptionFieldLabel = '';
  #hintSerialFieldLabel = '';
  #hintBindLabel = '';
  #hintBindButtonText = '';
  #hintBindButtonSuccessText = '';
  #hintBindButtonFailText = '';
  #hintBindAboutText = '';
  #saveBtnText = '';
  #applyBtnText = '';
  #cancelBtnText = '';

  checkSelector = (element) => {
    let selector = null;
    if( element.getAttribute('id') ){
      selector = '#' + element.getAttribute('id');
    } else if( element.getAttribute('class') ){
      selector = '.' + element.getAttribute('class').split(' ').join('.');
      //TODO: querySelectorAll
    }

    console.log('selector', selector);

    return selector;
  }

  countSibling = (element) => {
    let i=0;
    while((element=element.previousElementSibling)!=null) ++i;
    return i;
  }

  getHintElementInfo = (e) => {
    let element = e.target;
    let selector = this.checkSelector(element);
    let children = [];

    while( !selector ){
      children.unshift({
        sibling: this.countSibling(element)
      })
      element = element.parentElement;
      selector = this.checkSelector(element);
    }

    console.log('children', children);
    console.log('element', element);
    console.log('selector', selector);

    return selector ? {
      selector: selector,
      children: children
    } : null;
  };

  chooseItemListener = (e) => {
    e.preventDefault();
    const overlay = document.getElementById('popup-window-overlay-new-hint-popup');
    const newHintPopupWrapper = document.getElementById('new-hint-popup');

    this.#hintElementSelector = this.getHintElementInfo(e);

    if( this.#hintElementSelector ) {
      document.getElementById('button-bind-element').classList.remove('ui-btn-secondary');
      document.getElementById('button-bind-element').classList.add('ui-btn-success');
      document.getElementById('button-bind-element').innerText = this.#hintBindButtonSuccessText;
    } else {
      alert('Ошибка привязки элемента');
    }

    overlay.style.display = "block";
    newHintPopupWrapper.style.display = "block";

    document.removeEventListener("contextmenu", this.chooseItemListener);
    return false;
  };

  bindHintToElement = () => {
    const overlay = document.getElementById('popup-window-overlay-new-hint-popup');
    const newHintPopupWrapper = document.getElementById('new-hint-popup');

    overlay.style.display = "none";
    newHintPopupWrapper.style.display = "none";

    document.addEventListener( "contextmenu", this.chooseItemListener );
  }

  saveNewHint = () => {
    const hintName = document.getElementById('id-hint-name');
    const hintDescription = document.getElementById('id-hint-description');
    const hintSerial = document.getElementById('id-hint-serial');
    const bindButton = document.getElementById('button-bind-element');

    //
    // validation
    //
    let error = false;
    if( hintName.value === '' ) {
      hintName.parentElement.classList.add('ui-ctl-danger');
      error = true;
    } else {
      hintName.parentElement.classList.remove('ui-ctl-danger');
    }

    if( hintDescription.value === '' ) {
      hintDescription.parentElement.classList.add('ui-ctl-danger');
      error = true;
    } else {
      hintDescription.parentElement.classList.remove('ui-ctl-danger');
    }

    if( hintSerial.value === '' ) {
      hintSerial.parentElement.classList.add('ui-ctl-danger');
      error = true;
    } else {
      hintSerial.parentElement.classList.remove('ui-ctl-danger');
    }

    if( !this.#hintElementSelector ) {
      error = true;
      bindButton.classList.remove('ui-btn-secondary');
      bindButton.classList.remove('ui-btn-success');
      bindButton.classList.add('ui-btn-danger');
    }

    if( error ){
      return false;
    }

    saveHintToStorage({
      ID: this.#hintId,
      TYPE: 'hint',
      CURRENT_PAGE_URL: this.#currentPageUrl,
      CREATED_BY: "UNKNOWN",
      DATE_EDIT: new Date(),
      DATE_CREATE: new Date(),
      SORT: hintSerial.value,
      ACTIVE: true,
      GROUP_ID: this.#hintGroupId,
      NAME: hintName.value,
      DETAIL_TEXT: hintDescription.value,
      HINT_ELEMENT: this.#hintElementSelector,
    })

    return true;
  }

  closeHintPopup = () => {
    const hintName = document.getElementById('id-hint-name');
    const hintDescription = document.getElementById('id-hint-description');
    const hintSerial = document.getElementById('id-hint-serial');
    const bindButton = document.getElementById("button-bind-element");

    //
    // clear fields
    //
    hintName.value = '';
    hintDescription.value = '';
    hintSerial.value = 500;
    bindButton.classList.remove('ui-btn-success');
    bindButton.classList.remove('ui-btn-danger');
    bindButton.classList.add('ui-btn-secondary');

    this.#popup.destroy();
    if( this.#hintGroupId !== null ){
      setTimeout(() => getHintsListPopup(groupId).show(), 500);
    } else {
      setTimeout(() => getHintsListPopup().show(), 500);
    }
  }

  onSaveHintButtonPress = () => {
    if( this.saveNewHint() ){
      this.closeHintPopup();
    }
  }

  onApplyHintButtonPress = () => {
    this.saveNewHint();
  }

  onCancelHintButtonPress = () => {
    this.closeHintPopup();
  }

  render = () => {
    this.#popup = BX.PopupWindowManager.create(
      "new-hint-popup",
      BX("element"),
      {
        //
        // ширина окна
        //
        width: 500,
        min_width: 500,
        //
        // высота окна
        //
        height: 540,
        min_height: 500,
        //
        // z-index
        //
        zIndex: 100,
        //
        // иконка закрытия окна
        //
        closeIcon: {
          opacity: 0.6,
        },
        closeByEsc: true,
        darkMode: false,
        autoHide: true,
        draggable: true,
        resizable: false,
        lightShadow: true,
        angle: false,
        overlay: {
          backgroundColor: "black",
          opacity: 500,
        },
        //
        // заголовок
        //
        titleBar: this.#hintWindowTitle,
        //
        // контент
        //
        content: BX.create({
          tag: "div",
          props: {
            className: "form-container"
          },
          children: [
            BX.create({
              tag: "div",
              props: {
                className: "new-hint-form-label-container",
              },
              children: [
                BX.create({
                  tag: "label",
                  props: {
                    for: "id-hint-name",
                    className: "new-hint-form-label"
                  },
                  text: this.#hintNameFieldLabel,
                }),
              ],
            }),
            BX.create({
              tag: "div",
              props: {
                className: "ui-ctl ui-ctl-textbox ui-ctl-w100",
              },
              children: [
                BX.create({
                  tag: "input",
                  props: {
                    id: "id-hint-name",
                    type: "text",
                    className: "ui-ctl-element",
                    placeholder: this.#hintNameFieldLabel,
                    name: "hintName",
                    value: this.#hintNameFieldValue,
                  },
                }),
              ],
            }),
            BX.create({
              tag: "div",
              props: {
                className: "new-hint-form-label-container with-top-margin",
              },
              children: [
                BX.create({
                  tag: "label",
                  props: {
                    for: "id-hint-description",
                    className: "new-hint-form-label"
                  },
                  text: this.#hintDescriptionFieldLabel,
                }),
              ],
            }),
            BX.create({
              tag: "div",
              props: {
                className: "ui-ctl ui-ctl-textarea",
              },
              children: [
                BX.create({
                  tag: "textarea",
                  props: {
                    id: "id-hint-description",
                    name: "hintDescription",
                    rows: 3,
                    className: "ui-ctl-element",
                    placeholder: this.#hintDescriptionFieldLabel,
                    value: this.#hintDescriptionFieldValue,
                  },
                })
              ],
            }),
            BX.create({
              tag: "div",
              props: {
                className: "hint-form-row-container",
              },
              children: [
                BX.create({
                  tag: "div",
                  props: {
                    className: "hint-form-block-50 left",
                  },
                  children: [
                    BX.create({
                      tag: "div",
                      props: {
                        className: "new-hint-form-label-container",
                      },
                      children: [
                        BX.create({
                          tag: "label",
                          props: {
                            for: "id-hint-serial",
                            className: "new-hint-form-label"
                          },
                          text: this.#hintSerialFieldLabel,
                        }),
                      ],
                    }),
                    BX.create({
                      tag: "div",
                      props: {
                        className: "ui-ctl ui-ctl-textbox",
                      },
                      children: [
                        BX.create({
                          tag: "input",
                          props: {
                            type: "number",
                            id: "id-hint-serial",
                            name: "hintSerial",
                            className: "ui-ctl-element",
                            value: this.#hintSerialFieldValue,
                          },
                        }),
                      ],
                    }),
                  ]
                }),
                BX.create({
                  tag: "div",
                  props: {
                    className: "hint-form-block-50 right",
                  },
                  children: [
                    BX.create({
                      tag: "div",
                      props: {
                        className: "new-hint-form-label-container",
                      },
                      children: [
                        BX.create({
                          tag: "label",
                          props: {
                            for: "new-hint-number",
                            className: "new-hint-form-label"
                          },
                          text: this.#hintBindLabel,
                        }),
                      ],
                    }),
                    BX.create({
                      tag: "button",
                      text: this.#hintBindButtonText,
                      events: {
                        click: this.bindHintToElement,
                      },
                      props: {
                        id: "button-bind-element",
                        type: "button",
                        className: "ui-btn" + (this.#hintElementSelector === '' ? " ui-btn-secondary" : " ui-btn-success"),
                      },
                    }),
                  ]
                })
              ]
            }),
            BX.create({
              tag: "p",
              html:
                "<span class='asterix'>*</span> " +
                "<span class='text'>" + this.#hintBindAboutText + "</span>",
              props: {
                className: "new-hint-bottom-label",
              },
            }),
          ],
        }),
        buttons: [
          new BX.PopupWindowButton({
            text: this.#saveBtnText,
            id: "id-save-btn",
            className: "ui-btn ui-btn-success",
            events: {
              click: this.onSaveHintButtonPress,
            },
          }),
          new BX.PopupWindowButton({
            text: this.#applyBtnText,
            id: "id-apply-btn",
            className: "ui-btn ui-btn-light-border",
            events: {
              click: this.onApplyHintButtonPress,
            },
          }),
          new BX.PopupWindowButton({
            text: this.#cancelBtnText,
            id: "id-cancel-btn",
            className: "ui-btn ui-btn-link",
            events: {
              click: this.onCancelHintButtonPress,
            },
          }),
        ],
        events: {
          onPopupClose: this.onCancelHintButtonPress,
        },
      }
    );

    return this.#popup;
  }

  constructor(editHintId, groupId) {
    this.#currentPageUrl = window.location.href.split('?')[0];
    this.#hintId = parseInt(Math.random() * 100000).toString();

    this.#hintWindowTitle = 'Окно создания автоподсказки';
    this.#hintNameFieldLabel = 'Название';
    this.#hintDescriptionFieldLabel = 'Описание';
    this.#hintSerialFieldLabel = 'Сортировка';
    this.#hintBindLabel = 'Привязать к элементу';
    this.#hintBindButtonText = 'Привязать элемент';
    this.#hintBindButtonSuccessText = 'Элемент привязан';
    this.#hintBindButtonFailText = '';
    this.#hintBindAboutText = 'кликните на кнопку привязать элемент, а затем кликните правой кнопкой мыши по нужному элементу';
    this.#saveBtnText = 'Сохранить';
    this.#applyBtnText = 'Применить';
    this.#cancelBtnText = 'Отмена';

    if( typeof groupId !== 'undefined' && groupId !== null ){
      this.#hintGroupId = groupId;
    }

    if( typeof editHintId !== 'undefined' && editHintId !== null ){
      this.#hintId = editHintId;
      let hintFromStorage = getHintFromStorage(this.#hintId, groupId);
      if( hintFromStorage !== null ){
        this.#hintNameFieldValue = hintFromStorage.NAME;
        this.#hintDescriptionFieldValue = hintFromStorage.DETAIL_TEXT;
        this.#hintSerialFieldValue = hintFromStorage.SORT;
        this.#hintElementSelector = hintFromStorage.HINT_ELEMENT;
        this.#hintBindButtonText = 'Изменить элемент';
        this.#hintWindowTitle = 'Окно редактирования автоподсказки';
      }
    }
  }
}

