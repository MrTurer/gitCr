/**
 * Форма добавления новой группы
 */
let getNewGroupPopup;
BX.ready(function () {
  getNewGroupPopup = (editGroupId) => {
    const currentPageUrl = window.location.href.split('?')[0];

    let hintGroupId = parseInt(Math.random() * 100000).toString();
    let hintGroupNameFieldValue = ""
    let hintGroupSerialFieldValue = 500;
    let groupHints = [];
    const groupWindowTitle = 'Окно создания группы автоподсказок';
    const hintGroupNameFieldLabel = 'Название группы';
    const hintGroupSerialFieldLabel = 'Сортировка';
    const saveBtnText = 'Сохранить';
    const applyBtnText = 'Применить';
    const cancelBtnText = 'Отмена';

    const saveNewGroup = () => {
      const groupName = document.getElementById('id-hint-group-name');
      const groupSerial = document.getElementById('id-hint-group-serial');

      //
      // validation
      //
      let error = false;
      if( groupName.value === '' ) {
        groupName.parentElement.classList.add('ui-ctl-danger');
        error = true;
      } else {
        groupName.parentElement.classList.remove('ui-ctl-danger');
      }

      if( groupSerial.value === '' ) {
        groupSerial.parentElement.classList.add('ui-ctl-danger');
        error = true;
      } else {
        groupSerial.parentElement.classList.remove('ui-ctl-danger');
      }

      if( error ){
        return false;
      }

      saveGroupToStorage({
        ID: hintGroupId,
        TYPE: 'group',
        CURRENT_PAGE_URL: currentPageUrl,
        CREATED_BY: "UNKNOWN",
        DATE_EDIT: new Date(),
        DATE_CREATE: new Date(),
        SORT: groupSerial.value,
        ACTIVE: true,
        NAME: groupName.value,
        HINTS: groupHints,
      })
    }

    const closeGroupPopup = () => {
      const groupName = document.getElementById('id-hint-group-name');
      const groupSerial = document.getElementById('id-hint-group-serial');

      //
      // clear fields
      //
      groupName.value = '';
      groupSerial.value = 500;

      newGroupPopup.destroy();
      setTimeout(() => getHintsListPopup().show(), 500);
    }

    const onSaveGroupButtonPress = () => {
      saveNewGroup();
      closeGroupPopup();
    }

    const onApplyGroupHintButtonPress = () => {
      saveNewGroup();
    }

    const onCancelGroupButtonPress = () => {
      closeGroupPopup();
    }

    //
    // if edit
    //
    if( typeof editGroupId !== 'undefined' && editGroupId !== null ){
      hintGroupId = editGroupId;
      let groupFromStorage = getGroupFromStorage(hintGroupId);
      if( groupFromStorage !== null ){
        hintGroupNameFieldValue = groupFromStorage.NAME;
        hintGroupSerialFieldValue = groupFromStorage.SORT;
        groupHints = groupFromStorage.HINTS;
      }
    }

    const newGroupPopup = BX.PopupWindowManager.create(
      "new-group-popup",
      BX("element"),
      {
        //
        // ширина окна
        //
        width: 500,
        min_width: 500,
        //
        // высота окна
        //
        height: 400,
        min_height: 400,
        //
        // z-index
        //
        zIndex: 100,
        closeIcon: {
          opacity: 0.5,
        },
        closeByEsc: true,
        darkMode: false,
        autoHide: true,
        draggable: true,
        resizable: false,
        lightShadow: true,
        angle: false,
        overlay: {
          backgroundColor: "black",
          opacity: 500,
        },
        titleBar: groupWindowTitle,
        content: BX.create({
          tag: "div",
          props: {
            className: "form-container"
          },
          children: [
            BX.create({
              tag: "div",
              props: {
                className: "new-hint-form-label-container",
              },
              children: [
                BX.create({
                  tag: "label",
                  props: {
                    for: "id-hint-group-name",
                    className: "new-hint-form-label"
                  },
                  text: hintGroupNameFieldLabel,
                }),
              ],
            }),
            BX.create({
              tag: "div",
              props: {
                className: "ui-ctl ui-ctl-textbox ui-ctl-w100",
              },
              children: [
                BX.create({
                  tag: "input",
                  props: {
                    id: "id-hint-group-name",
                    type: "text",
                    className: "ui-ctl-element",
                    placeholder: hintGroupNameFieldLabel,
                    name: "hintName",
                    value: hintGroupNameFieldValue,
                  },
                }),
              ],
            }),
            BX.create({
              tag: "div",
              props: {
                className: "new-hint-form-label-container with-top-margin",
              },
              children: [
                BX.create({
                  tag: "label",
                  props: {
                    for: "id-hint-group-serial",
                    className: "new-hint-form-label"
                  },
                  text: hintGroupSerialFieldLabel,
                }),
              ],
            }),
            BX.create({
              tag: "div",
              props: {
                className: "ui-ctl ui-ctl-textbox ui-ctl-w100",
              },
              children: [
                BX.create({
                  tag: "input",
                  props: {
                    type: "number",
                    id: "id-hint-group-serial",
                    name: "hintSerial",
                    className: "ui-ctl-element",
                    value: hintGroupSerialFieldValue,
                  },
                }),
              ],
            }),
          ],
        }),
        buttons: [
          new BX.PopupWindowButton({
            text: saveBtnText,
            id: "id-save-btn",
            className: "ui-btn ui-btn-success",
            events: {
              click: onSaveGroupButtonPress,
            },
          }),
          new BX.PopupWindowButton({
            text: applyBtnText,
            id: "id-apply-btn",
            className: "ui-btn ui-btn-light-border",
            events: {
              click: onApplyGroupHintButtonPress,
            },
          }),
          new BX.PopupWindowButton({
            text: cancelBtnText,
            id: "id-cancel-btn",
            className: "ui-btn ui-btn-link",
            events: {
              click: onCancelGroupButtonPress,
            },
          }),
        ],
        events: {
          onPopupClose: onCancelGroupButtonPress,
        },
      }
    );

    return newGroupPopup;
  };
});

