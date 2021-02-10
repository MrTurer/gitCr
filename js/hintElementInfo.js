//
// Привязка подсказки к элементу на странице
//
let getHintElementInfo;
BX.ready(function () {
  getHintElementInfo = (flag, e, /* invisibleInput, */ viewData) => {
    if( flag ){
      // TODO: рекурсивно перебирать родителей и запоминать вложенность и порядок, чтобы однозначно определить элемент
      if (e.target.getAttribute("class") === "menu-item-link-text ") {
        hintSelector = e.target.parentElement.parentElement.getAttribute("id");
      } else {
        hintSelector =
          e.target.getAttribute("id") ||
          e.target.getAttribute("class") ||
          e.target.parentElement.getAttribute("id") ||
          e.target.parentElement.getAttribute("class");
      }

      //console.log(e.target);

      return hintSelector && (document.getElementById(hintSelector) ||
        document.body.querySelector("." + hintSelector.split(' ').join('.')));

    } else {
      // Определяем текущий элемент, к которому привязана подсказка,
      // что бы ниже вызова данной функции подсветить этот элемент на странице
      if (viewData) {
        let [
          currentHintElementIdentity,
          previousHintElementIdentity,
        ] = viewData;

        const currentHintElement = document.getElementById(currentHintElementIdentity) ||
          document.body.querySelector("." + currentHintElementIdentity);
        const previousHint = document.body.querySelector(
          ".hint-" + previousHintElementIdentity
        );
        return [currentHintElement, previousHint];
      }
    }
  };
});
