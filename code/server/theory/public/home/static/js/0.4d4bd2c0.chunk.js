(window.webpackJsonp=window.webpackJsonp||[]).push([[0],{70:function(t,e,n){"use strict";n.r(e);var s=n(2),a=n.n(s),r=n(8),i=n(0),c=n.n(i),u=n(20),o=n(21);e.default=function(){var t=Object(r.a)(a.a.mark((function t(e){var n;return a.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,Object(o.b)("api/getTests");case 2:n=t.sent.data,e.infoList.mypaper=new Array(3).fill([]),n.forEach((function(t){"\u5df2\u7ed3\u675f"!=t.status&&t.tested_time-t.test_time<0?e.infoList.mypaper[0].push([t.name,c.a.createElement(u.b,{onClick:e.request("exam/get")},"\u53c2\u52a0\u8003\u8bd5"),t.test_time-t.tested_time,t.score]):e.infoList.mypaper[1].push([t.name,t.stu_status,t.tested_time,t.score])}));case 5:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()}}]);