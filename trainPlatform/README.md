项目名：基于 vue-cli 的会议系统管理系统

## 1. 关于

#### 1.1. 介绍

基于 vue-cli 的会议系统管理系统，请求模块使用JsonServer提供API接口支持

#### 1.2. 技术栈

Vue、Webpack、ES6、vue-router、Vuex、axios、jsonServer 等。


## 2. 例子

#### 2.1. 线上的例子

暂未开通
```
用户：xxxxx
密码：xxxxx
```

#### 2.2. 服务端代码

暂未开通

## 3. 运行

#### 3.1. 命令

# 安装依赖
$ npm install

# 开发调试
$ npm run dev

# 构建
$ npm run build

## 4. 对 vue-cli 的一些修改和增强

#### 4.1. 用 axios 作为 ajax 方案

官方已经不推荐 vue-resource 作为 ajax 方案，请用 axios 代替。

```bash
# 安装 axios（还没弄）
$ npm install --save axios
```

#### 4.2. 添加 polyfill（还没弄）

按需引入 polyfill，提高浏览器兼容性。

```bash
# 安装 core-js
$ npm install --save core-js
```
polyfill 在 /src/utils/polyfill.js 文件中引入：
```js
import 'core-js/es6/promise'
```

#### 4.3. 用 Vuex 做状态管理

```bash
# 安装 vuex
$ npm install --save vuex
```

## 5. 规范

#### 5.1. 项目结构
```
|-- build                            // Webpack 项目构建
|-- config                           // 项目开发环境配置
|-- node_modules                     // 项目开发依赖包
|-- src                              // 源码目录
|   |-- pages                        // 业务代码
|       |-- college                  // 院校管理模块
|       |-- company                  // 公司管理模块
|       |-- practice                 // 顶岗实习
|       |-- home                     // 首页
|       |-- requirement              // 校企需求互通
|       |-- tracking                 // 毕业生追踪调查
|       |-- statistics               // 统计报表
|       |-- system                   // 系统权限模块
|   |-- components                   // 公共组件
|   |-- common                       // 公共静态文件
|   |-- router                       // 路由配置
|       |-- routes                   // 各业务模块路由配置
|   |-- store                        // Vuex 状态管理
|       |-- actions.js               // 根级别的 actions
|       |-- getters.js               // 根级别的 getters
|       |-- mutations.js             // 根级别的 mutations
|       |-- types.js                 // 根级别的 mutation types
|   |-- utils                        // 工具集合
|   |-- App.vue                      // 页面入口
|   |-- main.js                      // 程序入口，加载各种公共组件
|-- static                           // 静态文件，如：图片、JSON 数据等
|-- .babelrc                         // babel-loader 配置
|-- .editorconfig                    // 定义代码格式
|-- package.json                     // 项目基本信息
```
#### 5.2. 组件的命名规范

按照 vue-cli 的 Hello 示例，组件（这里说的是组件文件夹）命名应遵循帕斯卡（pascal）命名法，如：MyComponent。
> 当然，也有很多人喜欢命名成 my-component 的形式。这个规范不是强制性的，你可以选一种适合自己的。

#### 5.3. 公用组件规范

公用组件放在 /src/components 下。
```
|-- src                              // 源码目录
|   |-- components                   // 公用组件
|       |-- MyComponent              // MyComponent 组件
|           |-- index.vue            // MyComponent 的入口
|           |-- styles               // MyComponent 的样式
|               |-- css             // MyComponent 的样式入口
|               |-- images           // MyComponent 的图片
|               |-- fonts            // MyComponent 的字体
|           |-- utils                // MyComponent 的工具集合
|           |-- components           // MyComponent 的子组件
|               |-- ChildComponent   // MyComponent 的子组件 ChildComponent，组件规范和 MyComponent 一致
```
#### 5.4. 业务组件规范

业务组件放在 /src/pages 下，也就是一个页面，对应一个路由。规范和公用组件一直。
