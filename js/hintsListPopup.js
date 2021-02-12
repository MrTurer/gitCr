/**
 * Список подсказок
 */
let getHintsListPopup;
BX.ready(function () {
  getHintsListPopup = (groupId) => {
    let hintsListPopup = null;
    const hintsListWindowTitle = 'Список автоподсказок';
    const hintsListItemName = 'Название подсказки';
    const hintsListItemType = 'Тип';
    const hintsListItemTypeHint = 'Одиночная';
    const hintsListItemTypeGroup = 'Группа';
    const hintsListItemSort = 'Сортировка';
    const hintsListItemActions = 'Действия';
    const addHintButtonText = 'Добавить';
    const addHintGroupButtonText = 'Добавить группу';
    const editButtonText = 'Редактировать';
    const deleteButtonText = 'Удалить';
    const hideButtonText = 'Скрыть';
    const backButtonText = 'Назад';

    const getHintItems = () => {
      let hintsItemsList = typeof groupId === 'undefined'
        ? getHintsGeneralList()
        : getHintsInGroupList(groupId);

      return hintsItemsList.map((item, index) =>
        BX.create({
          tag: "tr",
          props: {
            id: 'id-list-item-' + item.ID,
          },
          children: [
            BX.create({
              tag: "th",
              text: index + 1,
            }),
            BX.create({
              tag: "td",
              children: [
                item.TYPE === 'group'
                ?
                BX.create({
                  tag: "button",
                  text: item.NAME,
                  props: {
                    className: 'ui-btn ui-btn-link',
                  },
                  events: {
                    click: () => onOpenGroupPress(item.ID)
                  }
                })
                :
                BX.create({
                  tag: "span",
                  text: item.NAME,
                }),
              ]
            }),
            BX.create({
              tag: "td",
              text: item.TYPE === 'group' ? hintsListItemTypeGroup : hintsListItemTypeHint,
            }),
            BX.create({
              tag: "td",
              text: item.SORT,
            }),
            BX.create({
              tag: "td",
              props: {
                className: 'last-column'
              },
              children: [
                BX.create({
                  tag: "button",
                  title: hideButtonText,
                  props: {
                    className: 'ui-btn ui-btn-danger ui-btn-icon-pause',
                    id: `header-hint-hide-toggle-${index}`,
                  },
                  events: {
                    click: function () {
                      item.ACTIVE = !item.ACTIVE;

                      if( item.TYPE === 'group' ){
                        saveGroupToStorage(item);
                      } else {
                        saveHintToStorage(item);
                      }

                      if( item.ACTIVE ){
                        document.getElementById(`header-hint-hide-toggle-${index}`)
                          .classList.remove('ui-btn-icon-start');
                        document.getElementById(`header-hint-hide-toggle-${index}`)
                          .classList.remove('ui-btn-success');
                        document.getElementById(`header-hint-hide-toggle-${index}`)
                          .classList.add('ui-btn-icon-pause');
                        document.getElementById(`header-hint-hide-toggle-${index}`)
                          .classList.add('ui-btn-danger');
                      } else {
                        document.getElementById(`header-hint-hide-toggle-${index}`)
                          .classList.remove('ui-btn-icon-pause');
                        document.getElementById(`header-hint-hide-toggle-${index}`)
                          .classList.remove('ui-btn-danger');
                        document.getElementById(`header-hint-hide-toggle-${index}`)
                          .classList.add('ui-btn-icon-start');
                        document.getElementById(`header-hint-hide-toggle-${index}`)
                          .classList.add('ui-btn-success');
                      }
                    },
                  },
                }),
                BX.create({
                  tag: "button",
                  title: editButtonText,
                  props: {
                    className: "ui-btn ui-btn-secondary ui-btn-icon-edit",
                  },
                  events: {
                    click: function () {
                      console.log('item.ID', item.ID);
                      if( item.TYPE === 'group' ){
                        setTimeout(getNewGroupPopup(item.ID).show(), 2000);
                      } else {
                        setTimeout(getNewHintPopup(item.ID, groupId).show(), 2000);
                      }
                    },
                  },
                }),
                BX.create({
                  tag: "button",
                  title: deleteButtonText,
                  props: {
                    className: "ui-btn ui-btn-secondary ui-btn-icon-remove",
                  },
                  events: {
                    click: () => {
                      deleteGroupOrSingleHint(item.ID);
                      BX.remove(BX('id-list-item-' + item.ID));
                    },
                  },
                }),
              ]
            }),
          ],
        })
      );
    };

    const onOpenGroupPress = (groupId) => {
      hintsListPopup.destroy();
      setTimeout(() => {
        getHintsListPopup(groupId).show();
      }, 300)
    }

    hintsListPopup = BX.PopupWindowManager.create(
      "popup-hints-list",
      BX("element"),
      {
        //
        // ширина окна
        //
        width: 550,
        min_width: 550,
        //
        // высота окна
        //
        height: 500,
        min_height: 500,
        //
        // z-index
        //
        zIndex: 100,
        closeIcon: {
          opacity: 0.5,
        },
        closeByEsc: true,
        autoHide: true,
        draggable: true,
        resizable: false,
        lightShadow: true,
        angle: false,
        overlay: {
          backgroundColor: "black",
          opacity: 500,
        },
        titleBar: hintsListWindowTitle,
        content: BX.create({
          tag: "div",
          props: { className: "hints-list-container" },
          children: [
            BX.create({
              tag: "table",
              props: {
                className: "hints-table",
              },
              children: [
                BX.create({
                  tag: "thead",
                  props: {
                    className: "hints-table-head",
                  },
                  children: [
                    BX.create({
                      tag: "tr",
                      children: [
                        BX.create({
                          tag: "th",
                          text: "№",
                        }),
                        BX.create({
                          tag: "th",
                          text: hintsListItemName,
                        }),
                        BX.create({
                          tag: "th",
                          text: hintsListItemType,
                        }),
                        BX.create({
                          tag: "th",
                          text: hintsListItemSort,
                        }),
                        BX.create({
                          tag: "th",
                          text: hintsListItemActions,
                          props: {
                            className: 'last-column'
                          }
                        }),
                      ]
                    })
                  ]
                }),
                BX.create({
                  tag: "tbody",
                  props: {
                    className: "hints-table-body"
                  },
                  children: [
                    ...getHintItems(),
                  ]
                })
              ],
            }),
          ],
        }),
        buttons: [
          new BX.PopupWindowButton({
            text: addHintButtonText,
            id: "id-add-hint-btn",
            className: "ui-btn ui-btn-success",
            events: {
              click: function () {
                hintsListPopup.destroy();
                setTimeout(getNewHintPopup(null, groupId).show(), 2000);
              },
            },
          }),
          typeof groupId === 'undefined'
          ?
          new BX.PopupWindowButton({
            text: addHintGroupButtonText,
            id: "id-add-hint-group-btn",
            className: "ui-btn ui-btn-primary",
            events: {
              click: function () {
                hintsListPopup.destroy();
                setTimeout(getNewGroupPopup().show(), 2000);
              },
            },
          })
          :
          new BX.PopupWindowButton({
            text: backButtonText,
            id: "id-back-btn",
            className: "ui-btn ui-btn-primary",
            events: {
              click: function () {
                hintsListPopup.destroy();
                setTimeout(getHintsListPopup().show(), 2000);
              },
            },
          }),
        ],
        events: {
          onPopupClose: function () {
            hintsListPopup.destroy();
          },
        },
      }
    );

    return hintsListPopup;
  };
});
