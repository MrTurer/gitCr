/**
 * Форма добавления новой подсказки
 */
let getNewHintPopup;
BX.ready(function () {
  getNewHintPopup = (editHintId, groupId) => {
    const currentPageUrl = window.location.href.split('?')[0];

    let bindElement = null;
    let hintId = parseInt(Math.random() * 100000).toString();
    let hintNameFieldValue = ""
    let hintDescriptionFieldValue = "";
    let hintSerialFieldValue = 500;
    let hintGroupId = null;
    let hintElementSelector = '';
    const hintWindowTitle = 'Окно создания автоподсказки';
    const hintNameFieldLabel = 'Название';
    const hintDescriptionFieldLabel = 'Описание';
    const hintSerialFieldLabel = 'Сортировка';
    const hintBindLabel = 'Привязать к элементу';
    let hintBindButtonText = 'Привязать элемент';
    const hintBindButtonSuccessText = 'Элемент привязан';
    const hintBindButtonFailText = '';
    const hintBindAboutText = 'кликните на кнопку привязать элемент, а затем кликните правой кнопкой мыши по нужному элементу';
    const saveBtnText = 'Сохранить';
    const applyBtnText = 'Применить';
    const cancelBtnText = 'Отмена';

    const getHintElementInfo = (e) => {
      // TODO: рекурсивно перебирать родителей и запоминать вложенность и порядок, чтобы однозначно определить элемент
      if (e.target.getAttribute("class") === "menu-item-link-text ") {
        hintElementSelector = e.target.parentElement.parentElement.getAttribute("id");
      } else {
        hintElementSelector =
          e.target.getAttribute("id") ||
          e.target.getAttribute("class") ||
          e.target.parentElement.getAttribute("id") ||
          e.target.parentElement.getAttribute("class");
      }

      return hintElementSelector && (document.getElementById(hintElementSelector) ||
        document.body.querySelector("." + hintElementSelector.split(' ').join('.')));
    };

    const bindHintToElement = () => {
      const overlay = document.getElementById('popup-window-overlay-new-hint-popup');
      const newHintPopupWrapper = document.getElementById('new-hint-popup');
      overlay.style.display = "none";
      newHintPopupWrapper.style.display = "none";

      const chooseItemListener = (e) => {
        $(function () {
          e.preventDefault();

          bindElement = getHintElementInfo(e);

          if( bindElement ) {
            document.getElementById('button-bind-element').classList.remove('ui-btn-secondary');
            document.getElementById('button-bind-element').classList.add('ui-btn-success');
            document.getElementById('button-bind-element').innerText = hintBindButtonSuccessText;
          } else {
            alert('Ошибка привязки элемента');
          }

          overlay.style.display = "block";
          newHintPopupWrapper.style.display = "block";

          document.removeEventListener("contextmenu", chooseItemListener);
          return false;
        });
      };
      document.addEventListener( "contextmenu", chooseItemListener );
    }

    const saveNewHint = () => {
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

      if( !bindElement ) {
        error = true;
        bindButton.classList.remove('ui-btn-secondary');
        bindButton.classList.remove('ui-btn-success');
        bindButton.classList.add('ui-btn-danger');
      }

      if( error ){
        return false;
      }

      saveHintToStorage({
        ID: hintId,
        TYPE: 'hint',
        CURRENT_PAGE_URL: currentPageUrl,
        CREATED_BY: "UNKNOWN",
        DATE_EDIT: new Date(),
        DATE_CREATE: new Date(),
        SORT: hintSerial.value,
        ACTIVE: true,
        GROUP_ID: hintGroupId,
        NAME: hintName.value,
        DETAIL_TEXT: hintDescription.value,
        HINT_ELEMENT: hintElementSelector,
      })

      return true;
    }

    const closeHintPopup = () => {
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

      newHintPopup.destroy();
      if( typeof groupId !== 'undefined' && groupId !== null ){
        setTimeout(() => getHintsListPopup(groupId).show(), 500);
      } else {
        setTimeout(() => getHintsListPopup().show(), 500);
      }
    }

    const onSaveHintButtonPress = () => {
      if( saveNewHint() ){
        closeHintPopup();
      }
    }

    const onApplyHintButtonPress = () => {
      saveNewHint();
    }

    const onCancelHintButtonPress = () => {
      closeHintPopup();
    }

    //
    // if edit
    //
    if( typeof groupId !== 'undefined' && groupId !== null ){
      hintGroupId = groupId;
    }

    if( typeof editHintId !== 'undefined' && editHintId !== null ){
      hintId = editHintId;
      let hintFromStorage = getHintFromStorage(hintId, groupId);
      if( hintFromStorage !== null ){
        hintNameFieldValue = hintFromStorage.NAME;
        hintDescriptionFieldValue = hintFromStorage.DETAIL_TEXT;
        hintSerialFieldValue = hintFromStorage.SORT;
        hintElementSelector = hintFromStorage.HINT_ELEMENT;
        hintBindButtonText = 'Изменить элемент';
        bindElement = document.getElementById(hintElementSelector) ||
          document.body.querySelector("." + hintElementSelector.split(' ').join('.'))
      }
    }

    const newHintPopup = BX.PopupWindowManager.create(
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
        titleBar: hintWindowTitle,
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
                  text: hintNameFieldLabel,
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
                    placeholder: hintNameFieldLabel,
                    name: "hintName",
                    value: hintNameFieldValue,
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
                  text: hintDescriptionFieldLabel,
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
                    placeholder: hintDescriptionFieldLabel,
                    value: hintDescriptionFieldValue,
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
                          text: hintSerialFieldLabel,
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
                            value: hintSerialFieldValue,
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
                          text: hintBindLabel,
                        }),
                      ],
                    }),
                    BX.create({
                      tag: "button",
                      text: hintBindButtonText,
                      events: {
                        click: bindHintToElement,
                      },
                      props: {
                        id: "button-bind-element",
                        type: "button",
                        className: "ui-btn" + (hintElementSelector === '' ? " ui-btn-secondary" : " ui-btn-success"),
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
                "<span class='text'>" + hintBindAboutText + "</span>",
              props: {
                className: "new-hint-bottom-label",
              },
            }),
          ],
        }),
        buttons: [
          new BX.PopupWindowButton({
            text: saveBtnText,
            id: "id-save-btn",
            className: "ui-btn ui-btn-success",
            events: {
              click: onSaveHintButtonPress,
            },
          }),
          new BX.PopupWindowButton({
            text: applyBtnText,
            id: "id-apply-btn",
            className: "ui-btn ui-btn-light-border",
            events: {
              click: onApplyHintButtonPress,
            },
          }),
          new BX.PopupWindowButton({
            text: cancelBtnText,
            id: "id-cancel-btn",
            className: "ui-btn ui-btn-link",
            events: {
              click: onCancelHintButtonPress,
            },
          }),
        ],
        events: {
          onPopupClose: onCancelHintButtonPress,
        },
      }
    );
    return newHintPopup;
  };
});

