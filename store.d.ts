// store.d.ts

import { ComponentCustomProperties } from 'vue';
import { Store } from 'vuex';

declare module 'app.js' {
  // Declare your own store states.
  interface State {
      [key: string]: any;
  }

  interface ComponentCustomProperties {
    $store: Store<State>
  }
}