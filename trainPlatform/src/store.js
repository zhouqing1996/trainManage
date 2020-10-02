const STORAGE_KEY = 'questionnaire';

export default {
	fetch() {
		return JSON.parse(window.localStorage.getItem(STORAGE_KEY));
	},
	save(items) {
		window.localStorage.setIt
    em(STORAGE_KEY, JSON.stringify(items));
	}
}
