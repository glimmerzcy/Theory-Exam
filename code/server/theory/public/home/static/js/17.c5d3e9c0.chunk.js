(window.webpackJsonp=window.webpackJsonp||[]).push([[17],{85:function(e,a,n){"use strict";n.r(a);var t=n(2),i=n.n(t),r=n(8),p=n(21),s=n(16);a.default=function(){var e=Object(r.a)(i.a.mark((function e(a){var n;return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n=0==a.infoList.paper.head.aim?{paper_id:a.infoList.paper.head.id}:1==a.infoList.paper.head.aim?{paper_id:a.infoList.paper.head.id,college_codes:a.infoList.college_codes.map((function(e){return e.id}))}:{paper_id:a.infoList.paper.head.id,stu_ids:a.infoList.stu_table.map((function(e){return e[0]}))},e.next=3,Object(p.c)("api/college/paper/release/v1",n);case 3:if("succeed"!==e.sent.status){e.next=8;break}return e.next=7,window.showToast("\u53d1\u5e03\u6210\u529f");case 7:window.navigateTo(s.a.ExPaper.route);case 8:case"end":return e.stop()}}),e)})));return function(a){return e.apply(this,arguments)}}()}}]);