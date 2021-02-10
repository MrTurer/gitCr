//
// Привязка к пункту меню "Автоподсказки"
//
BX.ready(function () {
  BX.addCustomEvent("onPopupFirstShow", function (e) {
    if (e.uniquePopupId === "menu-popup-user-menu") {
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
});
