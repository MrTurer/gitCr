/**
 * Форма добавления новой подсказки
 */
let getNewHintPopup;
BX.ready(function () {
  getNewHintPopup = (currentHintItem) => {
    let bindElement = null;

    const newHintPopup = BX.PopupWindowManager.create(
      "new-hint-popup",
      BX("element"),
      {
        content: BX.create({
          tag: "div",
          props: {className: "formFieldsContainer"},
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
                    for: "reviewName",
                    className: "new-hint-form-label"
                  },
                  text: "Название автоподсказки",
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
                    id: "reviewName",
                    type: "text",
                    className: "ui-ctl-element",
                    placeholder: "Название автоподсказки",
                    name: "Название автоподсказки",
                    value: currentHintItem ? currentHintItem.NAME : "",
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
                    for: "reviewText",
                    className: "new-hint-form-label"
                  },
                  text: "Описание автоподсказки",
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
                    id: "reviewText",
                    name: "reviewText",
                    rows: 3,
                    className: "ui-ctl-element",
                    value: currentHintItem ? currentHintItem.DETAIL_TEXT : "",
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
                            for: "new-hint-number",
                            className: "new-hint-form-label"
                          },
                          text: "Какой по счёту подсказка будет выведена на экран?",
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
                            id: "new-hint-number",
                            name: "new-hint-number",
                            rows: 3,
                            className: "ui-ctl-element",
                            value: currentHintItem
                              ? currentHintItem.HINT_NUMBER
                              : "1",
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
                          text: "Привязать автоподсказку к элементу",
                        }),
                      ],
                    }),
                    BX.create({
                      tag: "div",
                      props: {
                        className: "element-not-binded",
                      },
                      children: [
                        BX.create({
                          tag: "button",
                          text: "Привязать элемент",
                          events: {
                            click: function () {
                              const overlay = document.getElementById(
                                "popup-window-overlay-new-hint-popup"
                              );
                              const newHintPopupWrapper = document.getElementById(
                                "new-hint-popup"
                              );
                              overlay.style.display = "none";
                              newHintPopupWrapper.style.display = "none";
                              const chooseItemListener = (e) => {
                                $(function () {
                                  e.preventDefault();

                                  bindElement = getHintElementInfo(true, e);

                                  if( !bindElement ) {
                                    alert('Ошибка привязки элемента');
                                    return false;
                                  }

                                  document.getElementById("new-hint-popup").classList.add("bind-success");
                                  overlay.style.display = "block";
                                  newHintPopupWrapper.style.display = "block";

                                  return false;
                                });
                              };
                              document.addEventListener(
                                "contextmenu",
                                chooseItemListener
                              );
                            },
                          },
                          props: {
                            id: "button-bind-element",
                            type: "button",
                            className: "ui-btn ui-btn-secondary",
                          },
                        }),
                      ],
                    }),
                    BX.create({
                      tag: "div",
                      props: {
                        className: "element-binded",
                      },
                      children: [
                        BX.create({
                          tag: "div",
                          props: {
                            className: "ui-alert ui-alert-success",
                          },
                          children: [
                            BX.create({
                              tag: "span",
                              html: "<strong>Элемент привязан</strong>",
                              props: {
                                className: "ui-alert-message",
                              }
                            }),
                          ],
                        }),
                      ],
                    }),
                  ]
                })
              ]
            }),
            BX.create({
              tag: "p",
              html:
                "<span class='asterix'>*</span> " +
                "<span class='text'>кликните на кнопку привязать элемент, " +
                "а затем кликните правой кнопкой мыши по нужному элементу</span>",
              props: {
                className: "new-hint-bottom-label",
              },
            }),
            BX.create({
              tag: "input",
              props: {
                type: "text",
                className: "invisible-field",
              },
            }),
          ],
        }),
        //
        // ширина окна
        //
        width: 500,
        //
        // высота окна
        //
        height: 540,
        //
        // z-index
        //
        zIndex: 100,
        closeIcon: {
          // объект со стилями для иконки закрытия, при null - иконки не будет
          opacity: 0.6,
        },
        titleBar: "Окно создания автоподсказки",
        closeByEsc: true, // закрытие окна по esc
        darkMode: false, // окно будет светлым или темным
        autoHide: true, // закрытие при клике вне окна
        draggable: true, // можно двигать или нет
        resizable: false,
        min_height: 500, // минимальная высота окна
        min_width: 500, // минимальная ширина окна
        lightShadow: true,
        angle: false, // появится уголок
        overlay: {
          backgroundColor: "black",
          opacity: 500,
        },
        buttons: [
          new BX.PopupWindowButton({
            text: "Сохранить", // текст кнопки
            id: "save-btn", // идентификатор
            className: "ui-btn ui-btn-success", // доп. классы
            events: {
              click: function () {
                const newHintInputName = document
                    .getElementById("new-hint-popup")
                    .querySelector("input"),
                  newHintTextArea = document
                    .getElementById("new-hint-popup")
                    .querySelector("textarea"),
                  newHintNumberInput = document
                    .getElementById("new-hint-popup")
                    .querySelector('input[type="number"]'),
                  newHintBindButton = document
                    .getElementById("button-bind-element");

                //
                // validation
                //
                let error = false;
                if( newHintInputName.value === '' ) {
                  newHintInputName.parentElement.classList.add('ui-ctl-danger');
                  error = true;
                } else {
                  newHintInputName.parentElement.classList.remove('ui-ctl-danger');
                }

                if( newHintTextArea.value === '' ) {
                  newHintTextArea.parentElement.classList.add('ui-ctl-danger');
                  error = true;
                } else {
                  newHintTextArea.parentElement.classList.remove('ui-ctl-danger');
                }

                if( newHintNumberInput.value === '' ) {
                  newHintNumberInput.parentElement.classList.add('ui-ctl-danger');
                  error = true;
                } else {
                  newHintNumberInput.parentElement.classList.remove('ui-ctl-danger');
                }

                if( !bindElement ) {
                  newHintBindButton.classList.remove('ui-btn-secondary');
                  newHintBindButton.classList.add('ui-btn-danger');
                }

                if( error ){
                  return false;
                }

                const hintInfoPerPage = {
                  CURRENT_PAGE_URL: currentPageUrl,
                  NAME: newHintInputName.value,
                  DETAIL_TEXT: newHintTextArea.value,
                  HINT_NUMBER: newHintNumberInput.value,
                  HINT_ELEMENT: hintSelector,
                  CREATED_BY: "UNKNOWN",
                  DATE_EDIT: new Date(),
                  DATE_CREATE: new Date(),
                  SORT: "unset",
                  ACTION: "add",
                  ACTIVE: true,
                };

                if (currentHintItem) {
                  hintsPerPage = hintsPerPage.filter(
                    (item) => item.NAME !== currentHintItem.NAME
                  );
                }

                hintsPerPage.push(hintInfoPerPage);
                if (!isEdit) {
                  hintsPerPage.forEach((hint, i) => {
                    const el3 = () => {
                      let el = document.querySelector(
                        `.${hint.HINT_ELEMENT}`
                      );
                      if (el === null) {
                        el = document.getElementById(`${hint.HINT_ELEMENT}`);
                      }
                      if (el === null) {
                        hint.HINT_ELEMENT = hint.HINT_ELEMENT.split(' ').join('.');
                        el = document.querySelector(
                          `.${hint.HINT_ELEMENT}`
                        );
                      }
                      if (el === null) {
                        hintsPerPage.splice(i, 1);
                        alert("Ошибка формирования подсказки");
                      }
                    };
                    el3();
                  });
                  if (isEdit) isEdit = false;
                }

                localStorage.setItem(
                  "hints-info-per-page",
                  JSON.stringify(hintsPerPage)
                );
                clearFields(
                  newHintInputName,
                  newHintTextArea,
                  newHintNumberInput
                );
                newHintPopup.close();
                setTimeout(() => getHintsListPopup().show(), 300);
              },
            },
          }),
          new BX.PopupWindowButton({
            text: "Отмена",
            id: "copy-btn",
            className: "ui-btn ui-btn-link",
            events: {
              click: function () {
                const newHintInput = document
                    .getElementById("new-hint-popup")
                    .querySelector("input"),
                  newHintTextArea = document
                    .getElementById("new-hint-popup")
                    .querySelector("textarea"),
                  newHintNumberInput = document
                    .getElementById("new-hint-popup")
                    .querySelector('input[type="number"]');
                clearFields(
                  newHintInput,
                  newHintTextArea,
                  newHintNumberInput
                );

                newHintPopup.destroy();
              },
            },
          }),
        ],
        events: {
          onPopupClose: function (popupWindow) {
            const newHintInput = document
                .getElementById("new-hint-popup")
                .querySelector("input"),
              newHintTextArea = document
                .getElementById("new-hint-popup")
                .querySelector("textarea"),
              newHintNumberInput = document
                .getElementById("new-hint-popup")
                .querySelector('input[type="number"]');
            clearFields(newHintInput, newHintTextArea, newHintNumberInput);

            popupWindow.destroy();
          },
        },
      }
    );
    return newHintPopup;
  };
});

