/* eslint-disable */
"use strict";
export default Plugin;


import Vue from 'vue'
import axios from 'axios'

Vue.use({
  install(Vue) {
    Vue.prototype.$http = axios.create({
      baseURL: 'http://www.sisgestao.pro/Teste/',
    })
  }
})