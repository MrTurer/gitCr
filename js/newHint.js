// TODO:(разбить код на интерфейсы)
// Корректно отображается в Edge(Chromium), Mozilla Firefox, Google Chrome


/*
ACTION: "add"
ACTIVE: true
CREATED_BY: "UNNOWN"
CURRENT_PAGE_URL: "http://tc-bazhen.loc/start/"
DATE_CREATE: "2021-02-09T06:39:46.573Z"
DATE_EDIT: "2021-02-09T06:39:46.573Z"
DETAIL_TEXT: "hint 1"
HINT_ELEMENT: "bx_left_menu_menu_analytics"
HINT_NUMBER: "1"
NAME: "hint_1"
SORT: "unset"
*/

// значение редактируемой подсказки
let currentHintItem = null;
// селектор (класс или id) элемента, к которому привязана подсказка
let hintSelector = null;
let isEdit = false;
let currentPageUrl;
let hintsPerPage;

BX.ready(function () {
  // По док-ции обработчик гарантирует, что дом доступен
  BX.bind(document, "readystatechange", function () {
    setTimeout(() => {
      currentPageUrl = window.location.href.split('?')[0];
      hintsPerPage = JSON.parse(localStorage.getItem("hints-info-per-page")) || [];

      console.log('hintsPerPage', hintsPerPage);

      //
      // Привязка к пункту меню "Автоподсказки"
      //
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

      // Отрисовка подсказок

      const renderHints = () => {
        if (
          hintsPerPage !== null &&
          hintsPerPage.length &&
          hintsPerPage[0].CURRENT_PAGE_URL === currentPageUrl
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
                  previousHintElementIdentity = hintsPerPage[count].HINT_ELEMENT.split('.').join('-'),
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
                  : document.querySelector(`.${hint.HINT_ELEMENT}`);


            const
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
            hintTemplate = `<div class="hint-${hint.HINT_ELEMENT.split('.').join('-')} hint"
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
    })
  });
});


/*const observer = new MutationObserver(list => {
  console.log('mutation list', list);
});
observer.observe(document.body, {attributes: true, childList: true, subtree: true});*/
