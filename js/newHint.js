// TODO:(разбить код на интерфейсы)
// Корректно отображается в Edge(Chromium), Mozilla Firefox, Google Chrome
BX.ready(function () {
  // По док-ции обработчик гарантирует, что дом доступен
  BX.bind(document, "readystatechange", function () {
    const currenPageUrl = window.location.href;
    let hintsPerPage =
      JSON.parse(localStorage.getItem("hints-info-per-page")) || [];
    // значение редактируемой подсказки
    let currentHintItem = null;
    // селектор (класс или id) элемента, к которому привязана подсказка
    let hintSelector = null;
    let isEdit = false;

    console.log(hintsPerPage);

    /**
     * Привязка подсказки к элементу на странице
     */
    const getHintElementInfo = (flag, e, /* invisibleInput, */ viewData) => {
      if (flag && e.target.getAttribute("class") == "menu-item-link-text ") {
        hintSelector = e.target.parentElement.parentElement.getAttribute("id");
      } else if (flag) {
        hintSelector =
          e.target.getAttribute("class") ||
          e.target.getAttribute("id") ||
          e.target.parentElement.getAttribute("class") ||
          e.target.parentElement.getAttribute("id");
      }
      // Определяем текущий элемент, к которому привязана подсказка,
      // что бы ниже вызова данной функции подсветить этот элемент на странице
      if (viewData && !flag) {
        let [
          currentHintElementIdentity,
          previousHintElementIdentity,
        ] = viewData;

        const currentHintElement =
          document.body.querySelector("." + currentHintElementIdentity) ||
          document.getElementById(currentHintElementIdentity);
        const previousHint = document.body.querySelector(
          ".hint-" + previousHintElementIdentity
        );
        return [currentHintElement, previousHint];
      }
    };

    /**
     * Очищение поля в форме создания новой подсказки
     */
    const clearFields = function (textInput, textArea, numberInput) {
      numberInput.value = "100";
      textInput.value = "";
      textArea.value = "";
    };

    /**
     * Форма добавления новой подсказки
     */
    let getNewHintPopup = (currentHintItem) => {
      const newHintPopup = BX.PopupWindowManager.create(
        "popup-message",
        BX("element"),
        {
          content: BX.create({
            tag: "div",
            props: { className: "formFieldsContainer" },
            children: [
              BX.create({
                tag: "div",
                props: {
                  className: "form-group form-group-new-hint",
                },
                children: [
                  BX.create({
                    tag: "label",
                    props: { for: "reviewName" },
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
                    props: { for: "reviewText" },
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
                    props: { for: "new-hint-number" },
                    text: "Какой по счёту подсказка будет выведена на экран?",
                  }),

                  BX.create({
                    tag: "input",
                    props: {
                      type: "number",
                      id: "new-hint-number",
                      name: "new-hint-number",
                      value: 100,
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
                      CURRENT_PAGE_URL: currenPageUrl,
                      NAME: newHintInputName.value,
                      DETAIL_TEXT: newHintTextArea.value,
                      HINT_NUMBER: newHintNumberInput.value,
                      HINT_ELEMENT: hintSelector,
                      CREATED_BY: "UNNOWN",
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
                  console.log(!isEdit);
                  if (!isEdit) {
                    hintsPerPage.forEach((hint, i) => {
                      const el3 = () => {
                        let el = document.querySelector(
                          `.${hint.HINT_ELEMENT}`
                        );
                        console.log(hint.HINT_ELEMENT, el);
                        if (el === null) {
                          el = document.getElementById(`${hint.HINT_ELEMENT}`);
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

    /**
     * Список подсказок
     */
    const getHintsListPopup = () => {
      const hintsListPopup = BX.PopupWindowManager.create(
        "popup-hints-list",
        BX("element"),
        {
          content: BX.create({
            tag: "div",
            props: { className: "formFieldsContainer4" },
            children: [
              BX.create({
                tag: "div",
                props: {
                  className: "form-group",
                },
                children: [
                  BX.create({
                    tag: "span",
                    text: "№",
                    props: {
                      className: "header-hint-number",
                    },
                  }),
                  BX.create({
                    tag: "span",
                    text: "Название подсказки",
                    props: {
                      className: "header-hint-title",
                    },
                  }),
                ],
              }),
              ...getHintItems(),
            ],
          }),
          width: 500, // ширина окна
          height: 500, // высота окна
          zIndex: 100, // z-index
          closeIcon: {
            // объект со стилями для иконки закрытия, при null - иконки не будет
            opacity: 0.5,
          },
          titleBar: "Список автоподсказок",
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
              text: "Добавить", // текст кнопки
              id: "add-btn", // идентификатор
              className: "ui-btn ui-btn-success", // доп. классы
              events: {
                click: function () {
                  hintsListPopup.close();
                  setTimeout(getNewHintPopup().show(), 2000);
                },
              },
            }),
          ],
          events: {
            onPopupClose: function (popupWindow) {
              popupWindow.destroy();
            },
          },
        }
      );
      return hintsListPopup;
    };

    /**
     * Один элемент из списка подсказок
     */
    const getHintItems = () => {
      return hintsPerPage
        .sort((a, b) => a.HINT_NUMBER - b.HINT_NUMBER)
        .map((hint, index) =>
          BX.create({
            tag: "div",
            props: {
              id: `form-group-list-${index}`,
              className: "form-group-list",
            },
            children: [
              BX.create({
                tag: "span",
                text: index + 1,
                props: {
                  className: "header-hint-number",
                },
              }),
              BX.create({
                tag: "span",
                text: hint.NAME,
                props: {
                  className: "header-hint-title",
                },
              }),
              BX.create({
                tag: "svg",
                html: `<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                    <g>
                      <g>
                        <path d="M508.177,245.995C503.607,240.897,393.682,121,256,121S8.394,240.897,3.823,245.995c-5.098,5.698-5.098,14.312,0,20.01
                          C8.394,271.103,118.32,391,256,391s247.606-119.897,252.177-124.995C513.274,260.307,513.274,251.693,508.177,245.995z M256,361
                          c-57.891,0-105-47.109-105-105s47.109-105,105-105s105,47.109,105,105S313.891,361,256,361z"/>
                      </g>
                    </g>
                    <g>
                      <g>
                        <path d="M271,226c0-15.09,7.491-28.365,18.887-36.53C279.661,184.235,268.255,181,256,181c-41.353,0-75,33.647-75,75
                          c0,41.353,33.647,75,75,75c37.024,0,67.668-27.034,73.722-62.358C299.516,278.367,271,255.522,271,226z"/>
                      </g>
                    </g>
                    </svg>`,
                title: "Скрыть",
                props: {
                  className: `header-hint-hide-toggle${
                    hint.ACTIVE === true ? " hint-active" : ""
                  }`,
                  id: `header-hint-hide-toggle-${index}`,
                },
                events: {
                  click: function () {
                    hintsPerPage = hintsPerPage.map((item) => {
                      if (item.NAME === hint.NAME) {
                        item.ACTIVE = !item.ACTIVE;
                      }

                      return item;
                    });
                    localStorage.setItem(
                      "hints-info-per-page",
                      JSON.stringify(hintsPerPage)
                    );
                    BX.toggleClass(
                      BX(`header-hint-hide-toggle-${index}`),
                      "hint-active"
                    );
                  },
                },
              }),
              BX.create({
                tag: "span",
                src: "",
                title: "Редактировать",
                props: {
                  className: "header-hint-edit",
                },
                events: {
                  click: function () {
                    isEdit = true;
                    currentHintItem = hintsPerPage.find(
                      (item) => item.NAME === hint.NAME
                    );
                    setTimeout(getNewHintPopup(currentHintItem).show(), 2000);
                  },
                },
              }),
              BX.create({
                tag: "span",
                src: "",
                title: "Удалить",
                props: {
                  className: "header-hint-delete",
                },
                events: {
                  click: () => {
                    hintsPerPage = hintsPerPage.filter(
                      (item) => item.NAME !== hint.NAME
                    );
                    localStorage.setItem(
                      "hints-info-per-page",
                      JSON.stringify(hintsPerPage)
                    );
                    BX.remove(BX(`form-group-list-${index}`));
                  },
                },
              }),
            ],
          })
        );
    };

    /**
     * Привязка к пункту меню "Автоподсказки"
     */
    BX.addCustomEvent("onPopupFirstShow", function (e) {
      if (e.uniquePopupId == "menu-popup-user-menu") {
        //получаем пункты меню
        var tmpElements = BX.findChildren(
          BX("menu-popup-user-menu"),
          {
            className: "menu-popup-item",
          },
          true,
          true
        );
        //выбираем предпоследний
        var targetElement = tmpElements[tmpElements.length - 1];

        //создаем кнопку
        var autoHintsButton = BX.create("span", {
          props: {
            className: "menu-popup-item",
          },
          html:
            '<span class="menu-popup-item-text">' + "Автоподсказки" + "</span>",
          events: {
            click: function () {
              const popup =
                hintsPerPage && hintsPerPage.length !== 0
                  ? getHintsListPopup()
                  : getNewHintPopup();
              popup.show();
            },
          },
        });

        //вставляем ее после элемента
        BX.insertBefore(autoHintsButton, targetElement);
      }
    });
    /**
     * Привязка к пункту меню "Автоподсказки"
     */

    // Отрисовка подсказок

    const renderHints = () => {
      if (
        JSON.parse(localStorage.getItem("hints-info-per-page")) !== null &&
        JSON.parse(localStorage.getItem("hints-info-per-page")).length &&
        JSON.parse(localStorage.getItem("hints-info-per-page"))[0]
          .CURRENT_PAGE_URL === currenPageUrl
      ) {
        let count = 0;
        const hintsElementsData = {},
          hintsTemplates = [],
          highlightedBlocks = [],
          // Обработчик для перехода к следующей подсказке,
          // а так же скрывание предыдущей подсказки, и стили для подсветки текущего элемента и затемнения фона.
          nextHintHandler = function (e) {
            if (
              e.target.getAttribute("class") === "next-hint-button" ||
              "hint-button-cancel"
            ) {
              const currentHintElementIdentity =
                  hintsPerPage[count].HINT_ELEMENT,
                previousHintElementIdentity = hintsPerPage[count].HINT_ELEMENT,
                viewDatas = [
                  currentHintElementIdentity,
                  previousHintElementIdentity,
                ],
                [currentHintElement, previousHint] = getHintElementInfo(
                  false,
                  null,
                  viewDatas
                ),
                highlightedNode = document.body.querySelector(
                  ".highlighted-block"
                );
              highlightedNode.remove();
              count = count + 1;

              document.body.insertAdjacentHTML(
                "beforeend",
                hintsTemplates[count]
              );

              document.body.insertAdjacentHTML(
                "beforeend",
                highlightedBlocks[count]
              );

              previousHint.remove();

              if (count === hintsTemplates.length)
                document.body.querySelector(".bg-black-opacity").remove();
            }
            return count;
          };

        document.body.style = "position: absolute;";

        hintsPerPage.forEach((hint, i) => {
          let hintPositionX, hintPositionY, margin, hintTemplate;

          const hintElement =
              document.querySelector(`.${hint.HINT_ELEMENT}`) === null
                ? document.getElementById(`${hint.HINT_ELEMENT}`)
                : document.querySelector(`.${hint.HINT_ELEMENT}`),
            hintElementClientRect = hintElement.getBoundingClientRect(),
            contentAreaMaxWidth = document.body.offsetWidth,
            contentAreaMaxHeight = document.body.offsetHeight,
            // TODO(часть элементов нужно позиционировать относительно документа
            //, часть относительно окна браузера)
            calculateHintPosition = () => {
              const x = hintsElementsData.positionX;
              const y = hintsElementsData.positionY;
              hintElementWidth = hintsElementsData.width;
              hintElementHeight = hintsElementsData.height;
              hintPositionX = x + hintElementWidth;
              hintPositionY = y + hintElementHeight;
              if (hintPositionX + 200 > contentAreaMaxWidth) {
                hintPositionX = x - 220;
              } else if (hintPositionY + 200 > contentAreaMaxHeight) {
                console.log(hintPositionY, hintElement, contentAreaMaxHeight);
                hintPositionY = y - 150;
              }
            };

          hintsElementsData.positionX =
            hintElementClientRect.left + pageXOffset;
          hintsElementsData.positionY = hintElementClientRect.top + pageYOffset;
          hintsElementsData.width = hintElementClientRect.width;
          hintsElementsData.height = hintElementClientRect.height;

          hintsElementsData.highlightedBlockPositionX =
            hintElementClientRect.left + pageXOffset;
          hintsElementsData.highlightedBlockPositionY =
            hintElementClientRect.top + pageYOffset;
          hintsElementsData.highlightedBlockWidth = hintElementClientRect.width;
          hintsElementsData.highlightedBlockHeight =
            hintElementClientRect.height;

          calculateHintPosition();

          // Шаблон подсказки
          hintTemplate = `<div class="hint-${hint.HINT_ELEMENT} hint"
              style="left: ${hintPositionX}px; top: ${hintPositionY}px;">
              <p class='hint-description-text'>${hint.DETAIL_TEXT}</p><div class="hint-description-button-wrapper">
              <button class="next-hint-button">Далее</button><button class="hint-button-cancel">Пропустить</button></div>
              </div>`;

          // Шаблон подсвечивающего блока
          highlightedBlockTemplate = `<div class="highlighted-block-${hint.HINT_ELEMENT} highlighted-block"
              style="left: ${hintsElementsData.highlightedBlockPositionX}px; top: ${hintsElementsData.highlightedBlockPositionY}px;
              width: ${hintsElementsData.highlightedBlockWidth}px;
              height: 30px;">
              </div>`;

          hintsTemplates.push(hintTemplate);
          highlightedBlocks.push(highlightedBlockTemplate);
        });

        document.body.insertAdjacentHTML(
          "afterbegin",
          '<div class="bg-black-opacity"></div>'
        );
        document.body.insertAdjacentHTML("beforeend", hintsTemplates[0]);
        document.body.insertAdjacentHTML("beforeend", highlightedBlocks[0]);
        document.body.addEventListener("click", nextHintHandler);
      }
    };
    renderHints();
  });
});
