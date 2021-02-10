/**
 * Один элемент из списка подсказок
 */
let getHintItems;
BX.ready(function () {
  getHintItems = () => {
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
                  setTimeout(getNewHintPopup(
                    currentHintItem
                  ).show(), 2000);
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
});
