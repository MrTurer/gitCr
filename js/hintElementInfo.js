/**
 * Привязка подсказки к элементу на странице
 */
let getHintElementInfo;
BX.ready(function () {
  getHintElementInfo = (flag, e, /* invisibleInput, */ viewData) => {
    if (flag && e.target.getAttribute("class") === "menu-item-link-text ") {
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
});
