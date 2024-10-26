/** S 是否H5端 **/
// #ifdef H5
const IS_H5 = true
// #endif

// #ifndef H5
const IS_H5 = false
// #endif
/** E 是否H5端 **/

/** S API BaseURL **/
// ! 域名
// let baseDomain = 'http://www.like2.net'
let baseDomain = "http://api.302210110.xyz"; // 测试服

// ! api地址
let baseURL = baseDomain + '/api'


/** E API BaseURL **/


module.exports = {
	HEADER: {
		'content-type': 'application/json'
	},
	baseURL,
	baseDomain					// API Base URL
}
