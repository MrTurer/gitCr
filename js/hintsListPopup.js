/**
 * Список подсказок
 */
let getHintsListPopup;
BX.ready(function () {
  getHintsListPopup = () => {
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
          new BX.PopupWindowButton({
            text: "Добавить группу", // текст кнопки
            id: "add-group-btn", // идентификатор
            className: "ui-btn ui-btn-primary", // доп. классы
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
});
