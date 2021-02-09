/**
 * Очищение поля в форме создания новой подсказки
 */
let clearFields;
BX.ready(function () {
  clearFields = function (textInput, textArea, numberInput) {
    numberInput.value = "100";
    textInput.value = "";
    textArea.value = "";
  };
});
