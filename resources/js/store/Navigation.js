const mapCurrentRoute = (fullPath, item) => {
    if (typeof item.children === 'object') {
        item.children = item.children.map(i => mapCurrentRoute(fullPath, i))

        return item;
    }

    item.current = fullPath === item.href || fullPath.startsWith(item.href);
    return item;
};

export default {
    state: {
        hideRootNav: Spork.getLocalStorage('hideRootNav', false),
    },

    getters: {
        navigation: (state) => Object.values(Features).filter(feature => feature.enabled).map(route => ({
            name: route.name,
            icon: route.icon,
            href: route.path,
        })).map((item) => mapCurrentRoute(Spork.router.currentRoute._value.fullPath, item)),
        hidingRootNav: (state) => state.hideRootNav,
    },
    actions: {
        toggleRootNav({ state }) {
            state.hideRootNav = !state.hideRootNav;
            Spork.setLocalStorage('hideRootNav', state.hideRootNav)
        }
    }
}