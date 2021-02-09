/**
 * Форма добавления новой подсказки
 */
let getNewHintPopup;
BX.ready(function () {
  getNewHintPopup = (currentHintItem) => {
    const newHintPopup = BX.PopupWindowManager.create(
      "popup-message",
      BX("element"),
      {
        content: BX.create({
          tag: "div",
          props: {className: "formFieldsContainer"},
          children: [
            BX.create({
              tag: "div",
              props: {
                className: "form-group form-group-new-hint",
              },
              children: [
                BX.create({
                  tag: "label",
                  props: {for: "reviewName"},
                  text: "Название автоподсказки",
                }),
                BX.create({
                  tag: "input",
                  props: {
                    id: "reviewName",
                    type: "text",
                    className: "form-control",
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
                className: "form-group form-group-new-hint",
              },
              children: [
                BX.create({
                  tag: "label",
                  props: {for: "reviewText"},
                  text: "Описание автоподсказки",
                }),
                BX.create({
                  tag: "textarea",
                  props: {
                    id: "reviewText",
                    name: "reviewText",
                    rows: 3,
                    className: "form-control",
                    value: currentHintItem ? currentHintItem.DETAIL_TEXT : "",
                  },
                }),
              ],
            }),
            BX.create({
              tag: "div",
              props: {
                className: "form-group  form-group-new-hint",
              },
              children: [
                BX.create({
                  tag: "label",
                  props: {for: "new-hint-number"},
                  text: "Какой по счёту подсказка будет выведена на экран?",
                }),

                BX.create({
                  tag: "input",
                  props: {
                    type: "number",
                    id: "new-hint-number",
                    name: "new-hint-number",
                    rows: 3,
                    className: "new-hint-number",
                    value: currentHintItem
                      ? currentHintItem.HINT_NUMBER
                      : "100",
                  },
                }),
              ],
            }),
            BX.create({
              tag: "button",
              text: "Привязать элемент",
              events: {
                click: function () {
                  const overlay = document.getElementById(
                    "popup-window-overlay-popup-message"
                  );
                  const newHintPopupWrapper = document.getElementById(
                    "popup-message"
                  );
                  overlay.style.display = "none";
                  newHintPopupWrapper.style.display = "none";
                  const chooseItemListener = (e) => {
                    $(function () {
                      e.preventDefault();
                      const popup = BX.PopupWindowManager.create(
                        "popup-message1",
                        BX("element"),
                        {
                          width: 400, // ширина окна
                          height: 100, // высота окна
                          zIndex: 100, // z-index
                          closeIcon: {
                            // объект со стилями для иконки закрытия, при null - иконки не будет
                            opacity: 1,
                          },
                          titleBar: "Элемент привязан, закройте и сохраните",
                          closeByEsc: true, // закрытие окна по esc
                          darkMode: false, // окно будет светлым или темным
                          autoHide: false, // закрытие при клике вне окна
                          draggable: true, // можно двигать или нет
                          resizable: true, // можно ресайзить
                          min_height: 100, // минимальная высота окна
                          min_width: 100, // минимальная ширина окна
                          lightShadow: true, // использовать светлую тень у окна
                          angle: false, // появится уголок
                          overlay: {
                            // объект со стилями фона
                            backgroundColor: "black",
                            opacity: 500,
                          },

                          events: {
                            onPopupShow: function () {
                              const bgGray = document.getElementById(
                                "popup-window-content-popup-message1"
                              );
                              bgGray.style.backgroundColor = "transparent";
                            },
                            onPopupClose: function (popupWindow) {
                              overlay.style.display = "block";
                              newHintPopupWrapper.style.display = "block";
                              document.removeEventListener(
                                "contextmenu",
                                chooseItemListener
                              );
                              popupWindow.destroy();
                            },
                          },
                        }
                      );
                      popup.show();
                      getHintElementInfo(true, e);
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
                className: "form-button-bind-element",
              },
            }),
            BX.create({
              tag: "p",
              text:
                "* кликните на кнопку привязать элемент," +
                "а затем кликните правой кнопкой мыши по нужному элементу",
              props: {
                className: "bind-button-label",
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
        width: 500, // ширина окна
        height: 500, // высота окна
        zIndex: 100, // z-index
        closeIcon: {
          // объект со стилями для иконки закрытия, при null - иконки не будет
          opacity: 0.5,
        },
        titleBar: "Окно создания автоподсказки",
        closeByEsc: true, // закрытие окна по esc
        darkMode: false, // окно будет светлым или темным
        autoHide: true, // закрытие при клике вне окна
        draggable: true, // можно двигать или нет
        resizable: true, // можно ресайзить
        min_height: 500, // минимальная высота окна
        min_width: 500, // минимальная ширина окна
        lightShadow: true, // использовать светлую тень у окна
        angle: false, // появится уголок
        overlay: {
          // объект со стилями фона
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
                    .getElementById("popup-message")
                    .querySelector("input"),
                  newHintTextArea = document
                    .getElementById("popup-message")
                    .querySelector("textarea"),
                  newHintNumberInput = document
                    .getElementById("popup-message")
                    .querySelector('input[type="number"]'),
                  hintInfoPerPage = {
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
            className: "ui-btn ui-btn-primary",
            events: {
              click: function () {
                const newHintInput = document
                    .getElementById("popup-message")
                    .querySelector("input"),
                  newHintTextArea = document
                    .getElementById("popup-message")
                    .querySelector("textarea"),
                  newHintNumberInput = document
                    .getElementById("popup-message")
                    .querySelector('input[type="number"]');
                clearFields(
                  newHintInput,
                  newHintTextArea,
                  newHintNumberInput
                );
              },
            },
          }),
        ],
        events: {
          onPopupClose: function (popupWindow) {
            const newHintInput = document
                .getElementById("popup-message")
                .querySelector("input"),
              newHintTextArea = document
                .getElementById("popup-message")
                .querySelector("textarea"),
              newHintNumberInput = document
                .getElementById("popup-message")
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

