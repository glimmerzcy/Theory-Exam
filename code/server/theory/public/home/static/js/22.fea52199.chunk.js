(window.webpackJsonp=window.webpackJsonp||[]).push([[22],{90:function(e,t,a){"use strict";a.r(t);var n=a(17),s=a(2),r=a.n(s),u=a(8),i=a(21),c=a(0),o=a.n(c),p=a(20),d=a(16);t.default=function(){var e=Object(u.a)(r.a.mark((function e(t){var a,s,c,m;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Promise.all([Object(u.a)(r.a.mark((function e(){return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(i.b)("api/college/login/status/v1");case 2:return e.abrupt("return",a=e.sent);case 3:case"end":return e.stop()}}),e)})))(),Object(u.a)(r.a.mark((function e(){return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(i.b)("api/loginStatus");case 2:return e.abrupt("return",s=e.sent);case 3:case"end":return e.stop()}}),e)})))()]);case 2:console.log("loginStatus",a,s),"succeed"===a.status?(t.state=Object(n.a)({},a,{status:"college",userName:a.name}),window.navigateTo(d.a.College.route)):"succeed"===s.status&&(c=s.data.stu_info,t.state=Object(n.a)({},c,{status:"student",userName:c.real_name}),t.infoList.mypaper=new Array(3).fill([]),m=s.data.papers,Object.keys(m).forEach((function(e){var a=m[e],n=new Date,s=new Date(a.started_at.replace(/-/g,"/")),r=new Date(a.ended_at.replace(/-/g,"/"));"\u5df2\u7ed3\u675f"!==a.status&&a.tested_time-a.test_time<0&&+a.is_exist&&s<=n&&n<=r?t.infoList.mypaper[0].push([a.name,o.a.createElement(p.b,{onClick:t.request("exam/get",{paper_id:a.id,title:a.name,tip:a.tip,duration:a.duration,ended_at:a.ended_at})},"\u53c2\u52a0\u8003\u8bd5"),a.test_time-a.tested_time,a.score]):"\u5df2\u7ed3\u675f"!=a.status&&a.tested_time-a.test_time<0&&+a.is_exist&&s>n?t.infoList.mypaper[1].push([a.name,a.stu_status,a.tested_time,a.score]):t.infoList.mypaper[2].push([a.name,a.stu_status,a.tested_time,a.score])})),window.navigateTo(d.a.Student.route));case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()}}]);