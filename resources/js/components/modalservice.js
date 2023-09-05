// modalService.js
const STORAGE_KEY = "LS_LAST_UPDATE";

const getStorageValue = () => {
  try {
    return JSON.parse(localStorage.getItem(STORAGE_KEY));
  } catch {
    return "";
  }
};

const setStorageValue = (value = "") =>
  localStorage.setItem(STORAGE_KEY, JSON.stringify(value));

const dateIsOld = () => {
  const today = new Date().toLocaleDateString();

  return getStorageValue() !== today;
};

const updateDate = () => {
  setStorageValue(new Date().toLocaleDateString());
};

export { dateIsOld, updateDate };

export default {
  dateIsOld,
  updateDate,
};