//
// значение редактируемой подсказки
//
let currentHintItem = null;
//
// селектор (класс или id) элемента, к которому привязана подсказка
//
let hintSelector = null;
let isEdit = false;
let currentPageUrl;
let hintsPerPage;

BX.ready(function () {
  //
  // По док-ции обработчик гарантирует, что дом доступен
  //
  BX.bind(document, "readystatechange", function () {
    setTimeout(() => {
      currentPageUrl = window.location.href.split('?')[0];
      hintsPerPage = JSON.parse(localStorage.getItem("hints-info-per-page")) || [];

      //
      // Отрисовка подсказок
      //
      //renderHints();
      renderHintsOld();
    })
  });
});
