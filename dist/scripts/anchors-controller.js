"use strict";jQuery(function(){function e(e){for(var r=0,o=n,i=0;r<o;){var a=(r+o)/2|0;e<=t.get(a).getBoundingClientRect().top+window.scrollY?o=a:(i=a,r=a+1)}return t.get(i)}jQuery("a[href^=#]").click(function(e){e.preventDefault();var t=jQuery(this).attr("href");jQuery("html, body").animate({scrollTop:jQuery(t).offset().top-80},350,function(){window.location.hash=t})});var t=jQuery("section.content .inner h2, section.content .inner h3"),n=t.length,r=(jQuery("body").hasClass("single-chapter")||jQuery("body").hasClass("single-article"))&&0!==n;jQuery(window).on("DOMContentLoaded load resize scroll",function(){if(r){var t="#"+e(window.scrollY+230).getAttribute("id");jQuery(".toc-item").removeClass("_reading"),jQuery(".toc-item[href='"+t+"']").addClass("_reading")}})});