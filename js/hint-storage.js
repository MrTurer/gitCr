/*
{
  ID (string),
  TYPE (enum("hint", "group")),
  CURRENT_PAGE_URL (string),
  CREATED_BY (?),
  DATE_EDIT (bigint),
  DATE_CREATE (bigint),
  SORT (int),
  ACTIVE (bool),
  NAME (string),
  HINTS:
  {
    ID (string),
    TYPE (enum("hint", "group")),
    CURRENT_PAGE_URL (string),
    CREATED_BY (?),
    DATE_EDIT (bigint),
    DATE_CREATE (bigint),
    SORT (int),
    ACTIVE (bool),
    GROUP_ID (int),
    NAME (string),
    DETAIL_TEXT (string),
    HINT_ELEMENT (string),
  }
}
*/

function getHintFromStorage(hintId, groupId){
  let items = JSON.parse(localStorage.getItem('hints-info-per-page'));

  if( items ){
    if( typeof groupId === 'undefined' || groupId === null ){
      let filteredItem = items.filter(item => item.ID === hintId);
      return filteredItem.length > 0 ? filteredItem[0] : null;
    } else {
      let filteredGroup = items.filter(item => item.ID === groupId);

      if( filteredGroup.length > 0 ){
        let filteredItem = filteredGroup[0].HINTS.filter(item => item.ID === hintId);
        return filteredItem.length > 0 ? filteredItem[0] : null;
      }
    }
  }

  return null;
}

function getGroupFromStorage(groupId){
  let items = JSON.parse(localStorage.getItem('hints-info-per-page'));

  if( items ){
    let filteredGroup = items.filter(item => item.ID === groupId);

    if( filteredGroup.length > 0 ){
      return filteredGroup[0];
    }
  }

  return null;
}

function saveHintToStorage(hint) {
  console.log(hint);
  let items = JSON.parse(localStorage.getItem('hints-info-per-page')) || [];

  if( hint.GROUP_ID ) {
    let filteredGroup = items.filter(item => item.ID === hint.GROUP_ID);
    if( filteredGroup.length > 0 ){
      filteredGroup[0].HINTS = filteredGroup[0].HINTS.filter(item => item.ID !== hint.ID);

      filteredGroup[0].HINTS.push(hint);

      items = items.filter(
        item => item.ID !== hint.GROUP_ID
      );

      items.push(filteredGroup[0]);
    }
  } else {
    if( items ){
      items = items.filter(
        item => item.ID !== hint.ID
      );
    }

    items.push(hint);
  }

  localStorage.setItem('hints-info-per-page', JSON.stringify(items));
}

function saveGroupToStorage(group) {
  let items = JSON.parse(localStorage.getItem('hints-info-per-page')) || [];

  if( items ){
    items = items.filter(
      item => item.ID !== group.ID
    );
  }

  items.push(group);

  localStorage.setItem('hints-info-per-page', JSON.stringify(items));
}

function getHintsGeneralListFromStorage() {
  let items = JSON.parse(localStorage.getItem("hints-info-per-page"));
  return items ? items.sort((a, b) => a.SORT - b.SORT) : [];
}

function getHintsInGroupListFromStorage(groupId) {
  let items = JSON.parse(localStorage.getItem("hints-info-per-page"));
  if( items ){
    let filteredGroup = items.filter(item => item.ID === groupId);
    if( filteredGroup ){
      return filteredGroup[0].HINTS ? filteredGroup[0].HINTS.sort((a, b) => a.SORT - b.SORT) : [];
    }
  }

  return [];
}

function deleteHintFromStorage(hintId) {
  let filteredHints = JSON.parse(localStorage.getItem('hints-info-per-page')).filter(
    item => item.ID !== hintId.ID
  );

  localStorage.setItem('hints-info-per-page', JSON.stringify(filteredHints));
}

function deleteGroupFromStorage(groupId) {
  let filteredHints = JSON.parse(localStorage.getItem('hints-info-per-page')).filter(
    item => item.ID !== groupId.ID
  );

  localStorage.setItem('hints-info-per-page', JSON.stringify(filteredHints));
}


