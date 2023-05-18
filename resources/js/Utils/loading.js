import { defineComponent, h } from 'vue';

const Loading = defineComponent({
  render() {
    return h('div', { class: 'loader' }, 'Loading...');
  }
});

export default Loading;