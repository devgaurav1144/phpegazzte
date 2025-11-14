if (function(t, e) {
        "object" == typeof module && "object" == typeof module.exports ? module.exports = t.document ? e(t, !0) : function(t) {
            if (!t.document) throw new Error("jQuery requires a window with a document");
            return e(t)
        } : e(t)
    }("undefined" != typeof window ? window : this, function(t, e) {
        function n(t) {
            var e = "length" in t && t.length,
                n = X.type(t);
            return "function" !== n && !X.isWindow(t) && (!(1 !== t.nodeType || !e) || "array" === n || 0 === e || "number" == typeof e && e > 0 && e - 1 in t)
        }

        function i(t, e, n) {
            if (X.isFunction(e)) return X.grep(t, function(t, i) {
                return !!e.call(t, i, t) !== n
            });
            if (e.nodeType) return X.grep(t, function(t) {
                return t === e !== n
            });
            if ("string" == typeof e) {
                if (et.test(e)) return X.filter(e, t, n);
                e = X.filter(e, t)
            }
            return X.grep(t, function(t) {
                return W.call(e, t) >= 0 !== n
            })
        }

        function o(t, e) {
            for (;
                (t = t[e]) && 1 !== t.nodeType;);
            return t
        }

        function r() {
            V.removeEventListener("DOMContentLoaded", r, !1), t.removeEventListener("load", r, !1), X.ready()
        }

        function s() {
            Object.defineProperty(this.cache = {}, 0, {
                get: function() {
                    return {}
                }
            }), this.expando = X.expando + s.uid++
        }

        function a(t, e, n) {
            var i;
            if (void 0 === n && 1 === t.nodeType)
                if (i = "data-" + e.replace(ft, "-$1").toLowerCase(), "string" == typeof(n = t.getAttribute(i))) {
                    try {
                        n = "true" === n || "false" !== n && ("null" === n ? null : +n + "" === n ? +n : dt.test(n) ? X.parseJSON(n) : n)
                    } catch (t) {}
                    pt.set(t, e, n)
                } else n = void 0;
            return n
        }

        function l() {
            return !0
        }

        function c() {
            return !1
        }

        function u() {
            try {
                return V.activeElement
            } catch (t) {}
        }

        function p(t, e) {
            return X.nodeName(t, "table") && X.nodeName(11 !== e.nodeType ? e : e.firstChild, "tr") ? t.getElementsByTagName("tbody")[0] || t.appendChild(t.ownerDocument.createElement("tbody")) : t
        }

        function d(t) {
            return t.type = (null !== t.getAttribute("type")) + "/" + t.type, t
        }

        function f(t) {
            var e = Nt.exec(t.type);
            return e ? t.type = e[1] : t.removeAttribute("type"), t
        }

        function h(t, e) {
            for (var n = 0, i = t.length; n < i; n++) ut.set(t[n], "globalEval", !e || ut.get(e[n], "globalEval"))
        }

        function g(t, e) {
            var n, i, o, r, s, a, l, c;
            if (1 === e.nodeType) {
                if (ut.hasData(t) && (r = ut.access(t), s = ut.set(e, r), c = r.events))
                    for (o in delete s.handle, s.events = {}, c)
                        for (n = 0, i = c[o].length; n < i; n++) X.event.add(e, o, c[o][n]);
                pt.hasData(t) && (a = pt.access(t), l = X.extend({}, a), pt.set(e, l))
            }
        }

        function m(t, e) {
            var n = t.getElementsByTagName ? t.getElementsByTagName(e || "*") : t.querySelectorAll ? t.querySelectorAll(e || "*") : [];
            return void 0 === e || e && X.nodeName(t, e) ? X.merge([t], n) : n
        }

        function v(t, e) {
            var n = e.nodeName.toLowerCase();
            "input" === n && vt.test(t.type) ? e.checked = t.checked : "input" !== n && "textarea" !== n || (e.defaultValue = t.defaultValue)
        }

        function y(e, n) {
            var i, o = X(n.createElement(e)).appendTo(n.body),
                r = t.getDefaultComputedStyle && (i = t.getDefaultComputedStyle(o[0])) ? i.display : X.css(o[0], "display");
            return o.detach(), r
        }

        function b(t) {
            var e = V,
                n = Ot[t];
            return n || ("none" !== (n = y(t, e)) && n || ((e = (jt = (jt || X("<iframe frameborder='0' width='0' height='0'/>")).appendTo(e.documentElement))[0].contentDocument).write(), e.close(), n = y(t, e), jt.detach()), Ot[t] = n), n
        }

        function x(t, e, n) {
            var i, o, r, s, a = t.style;
            return (n = n || Lt(t)) && (s = n.getPropertyValue(e) || n[e]), n && ("" !== s || X.contains(t.ownerDocument, t) || (s = X.style(t, e)), Rt.test(s) && It.test(e) && (i = a.width, o = a.minWidth, r = a.maxWidth, a.minWidth = a.maxWidth = a.width = s, s = n.width, a.width = i, a.minWidth = o, a.maxWidth = r)), void 0 !== s ? s + "" : s
        }

        function w(t, e) {
            return {
                get: function() {
                    return t() ? void delete this.get : (this.get = e).apply(this, arguments)
                }
            }
        }

        function T(t, e) {
            if (e in t) return e;
            for (var n = e[0].toUpperCase() + e.slice(1), i = e, o = Wt.length; o--;)
                if ((e = Wt[o] + n) in t) return e;
            return i
        }

        function C(t, e, n) {
            var i = Ht.exec(e);
            return i ? Math.max(0, i[1] - (n || 0)) + (i[2] || "px") : e
        }

        function E(t, e, n, i, o) {
            for (var r = n === (i ? "border" : "content") ? 4 : "width" === e ? 1 : 0, s = 0; r < 4; r += 2) "margin" === n && (s += X.css(t, n + gt[r], !0, o)), i ? ("content" === n && (s -= X.css(t, "padding" + gt[r], !0, o)), "margin" !== n && (s -= X.css(t, "border" + gt[r] + "Width", !0, o))) : (s += X.css(t, "padding" + gt[r], !0, o), "padding" !== n && (s += X.css(t, "border" + gt[r] + "Width", !0, o)));
            return s
        }

        function k(t, e, n) {
            var i = !0,
                o = "width" === e ? t.offsetWidth : t.offsetHeight,
                r = Lt(t),
                s = "border-box" === X.css(t, "boxSizing", !1, r);
            if (o <= 0 || null == o) {
                if (((o = x(t, e, r)) < 0 || null == o) && (o = t.style[e]), Rt.test(o)) return o;
                i = s && (U.boxSizingReliable() || o === t.style[e]), o = parseFloat(o) || 0
            }
            return o + E(t, e, n || (s ? "border" : "content"), i, r) + "px"
        }

        function S(t, e) {
            for (var n, i, o, r = [], s = 0, a = t.length; s < a; s++)(i = t[s]).style && (r[s] = ut.get(i, "olddisplay"), n = i.style.display, e ? (r[s] || "none" !== n || (i.style.display = ""), "" === i.style.display && mt(i) && (r[s] = ut.access(i, "olddisplay", b(i.nodeName)))) : (o = mt(i), "none" === n && o || ut.set(i, "olddisplay", o ? n : X.css(i, "display"))));
            for (s = 0; s < a; s++)(i = t[s]).style && (e && "none" !== i.style.display && "" !== i.style.display || (i.style.display = e ? r[s] || "" : "none"));
            return t
        }

        function $(t, e, n, i, o) {
            return new $.prototype.init(t, e, n, i, o)
        }

        function N() {
            return setTimeout(function() {
                Bt = void 0
            }), Bt = X.now()
        }

        function D(t, e) {
            var n, i = 0,
                o = {
                    height: t
                };
            for (e = e ? 1 : 0; i < 4; i += 2 - e) o["margin" + (n = gt[i])] = o["padding" + n] = t;
            return e && (o.opacity = o.width = t), o
        }

        function A(t, e, n) {
            for (var i, o = (Qt[e] || []).concat(Qt["*"]), r = 0, s = o.length; r < s; r++)
                if (i = o[r].call(n, e, t)) return i
        }

        function j(t, e, n) {
            var i, o, r = 0,
                s = Xt.length,
                a = X.Deferred().always(function() {
                    delete l.elem
                }),
                l = function() {
                    if (o) return !1;
                    for (var e = Bt || N(), n = Math.max(0, c.startTime + c.duration - e), i = 1 - (n / c.duration || 0), r = 0, s = c.tweens.length; r < s; r++) c.tweens[r].run(i);
                    return a.notifyWith(t, [c, i, n]), i < 1 && s ? n : (a.resolveWith(t, [c]), !1)
                },
                c = a.promise({
                    elem: t,
                    props: X.extend({}, e),
                    opts: X.extend(!0, {
                        specialEasing: {}
                    }, n),
                    originalProperties: e,
                    originalOptions: n,
                    startTime: Bt || N(),
                    duration: n.duration,
                    tweens: [],
                    createTween: function(e, n) {
                        var i = X.Tween(t, c.opts, e, n, c.opts.specialEasing[e] || c.opts.easing);
                        return c.tweens.push(i), i
                    },
                    stop: function(e) {
                        var n = 0,
                            i = e ? c.tweens.length : 0;
                        if (o) return this;
                        for (o = !0; n < i; n++) c.tweens[n].run(1);
                        return e ? a.resolveWith(t, [c, e]) : a.rejectWith(t, [c, e]), this
                    }
                }),
                u = c.props;
            for (function(t, e) {
                    var n, i, o, r, s;
                    for (n in t)
                        if (o = e[i = X.camelCase(n)], r = t[n], X.isArray(r) && (o = r[1], r = t[n] = r[0]), n !== i && (t[i] = r, delete t[n]), (s = X.cssHooks[i]) && "expand" in s)
                            for (n in r = s.expand(r), delete t[i], r) n in t || (t[n] = r[n], e[n] = o);
                        else e[i] = o
                }(u, c.opts.specialEasing); r < s; r++)
                if (i = Xt[r].call(c, t, u, c.opts)) return i;
            return X.map(u, A, c), X.isFunction(c.opts.start) && c.opts.start.call(t, c), X.fx.timer(X.extend(l, {
                elem: t,
                anim: c,
                queue: c.opts.queue
            })), c.progress(c.opts.progress).done(c.opts.done, c.opts.complete).fail(c.opts.fail).always(c.opts.always)
        }

        function O(t) {
            return function(e, n) {
                "string" != typeof e && (n = e, e = "*");
                var i, o = 0,
                    r = e.toLowerCase().match(at) || [];
                if (X.isFunction(n))
                    for (; i = r[o++];) "+" === i[0] ? (i = i.slice(1) || "*", (t[i] = t[i] || []).unshift(n)) : (t[i] = t[i] || []).push(n)
            }
        }

        function I(t, e, n, i) {
            function o(a) {
                var l;
                return r[a] = !0, X.each(t[a] || [], function(t, a) {
                    var c = a(e, n, i);
                    return "string" != typeof c || s || r[c] ? s ? !(l = c) : void 0 : (e.dataTypes.unshift(c), o(c), !1)
                }), l
            }
            var r = {},
                s = t === ce;
            return o(e.dataTypes[0]) || !r["*"] && o("*")
        }

        function R(t, e) {
            var n, i, o = X.ajaxSettings.flatOptions || {};
            for (n in e) void 0 !== e[n] && ((o[n] ? t : i || (i = {}))[n] = e[n]);
            return i && X.extend(!0, t, i), t
        }

        function L(t, e, n, i) {
            var o;
            if (X.isArray(e)) X.each(e, function(e, o) {
                n || he.test(t) ? i(t, o) : L(t + "[" + ("object" == typeof o ? e : "") + "]", o, n, i)
            });
            else if (n || "object" !== X.type(e)) i(t, e);
            else
                for (o in e) L(t + "[" + o + "]", e[o], n, i)
        }

        function P(t) {
            return X.isWindow(t) ? t : 9 === t.nodeType && t.defaultView
        }
        var H = [],
            q = H.slice,
            F = H.concat,
            M = H.push,
            W = H.indexOf,
            B = {},
            z = B.toString,
            _ = B.hasOwnProperty,
            U = {},
            V = t.document,
            X = function(t, e) {
                return new X.fn.init(t, e)
            },
            Q = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
            Y = /^-ms-/,
            G = /-([\da-z])/gi,
            J = function(t, e) {
                return e.toUpperCase()
            };
        X.fn = X.prototype = {
            jquery: "3.4.1",
            constructor: X,
            selector: "",
            length: 0,
            toArray: function() {
                return q.call(this)
            },
            get: function(t) {
                return null != t ? t < 0 ? this[t + this.length] : this[t] : q.call(this)
            },
            pushStack: function(t) {
                var e = X.merge(this.constructor(), t);
                return e.prevObject = this, e.context = this.context, e
            },
            each: function(t, e) {
                return X.each(this, t, e)
            },
            map: function(t) {
                return this.pushStack(X.map(this, function(e, n) {
                    return t.call(e, n, e)
                }))
            },
            slice: function() {
                return this.pushStack(q.apply(this, arguments))
            },
            first: function() {
                return this.eq(0)
            },
            last: function() {
                return this.eq(-1)
            },
            eq: function(t) {
                var e = this.length,
                    n = +t + (t < 0 ? e : 0);
                return this.pushStack(n >= 0 && n < e ? [this[n]] : [])
            },
            end: function() {
                return this.prevObject || this.constructor(null)
            },
            push: M,
            sort: H.sort,
            splice: H.splice
        }, X.extend = X.fn.extend = function() {
            var t, e, n, i, o, r, s = arguments[0] || {},
                a = 1,
                l = arguments.length,
                c = !1;
            for ("boolean" == typeof s && (c = s, s = arguments[a] || {}, a++), "object" == typeof s || X.isFunction(s) || (s = {}), a === l && (s = this, a--); a < l; a++)
                if (null != (t = arguments[a]))
                    for (e in t) n = s[e], s !== (i = t[e]) && (c && i && (X.isPlainObject(i) || (o = X.isArray(i))) ? (o ? (o = !1, r = n && X.isArray(n) ? n : []) : r = n && X.isPlainObject(n) ? n : {}, s[e] = X.extend(c, r, i)) : void 0 !== i && (s[e] = i));
            return s
        }, X.extend({
            expando: "jQuery" + ("3.4.1" + Math.random()).replace(/\D/g, ""),
            isReady: !0,
            error: function(t) {
                throw new Error(t)
            },
            noop: function() {},
            isFunction: function(t) {
                return "function" === X.type(t)
            },
            isArray: Array.isArray,
            isWindow: function(t) {
                return null != t && t === t.window
            },
            isNumeric: function(t) {
                return !X.isArray(t) && t - parseFloat(t) + 1 >= 0
            },
            isPlainObject: function(t) {
                return !("object" !== X.type(t) || t.nodeType || X.isWindow(t) || t.constructor && !_.call(t.constructor.prototype, "isPrototypeOf"))
            },
            isEmptyObject: function(t) {
                var e;
                for (e in t) return !1;
                return !0
            },
            type: function(t) {
                return null == t ? t + "" : "object" == typeof t || "function" == typeof t ? B[z.call(t)] || "object" : typeof t
            },
            globalEval: function(t) {
                var e, n = eval;
                (t = X.trim(t)) && (1 === t.indexOf("use strict") ? ((e = V.createElement("script")).text = t, V.head.appendChild(e).parentNode.removeChild(e)) : n(t))
            },
            camelCase: function(t) {
                return t.replace(Y, "ms-").replace(G, J)
            },
            nodeName: function(t, e) {
                return t.nodeName && t.nodeName.toLowerCase() === e.toLowerCase()
            },
            each: function(t, e, i) {
                var o = 0,
                    r = t.length,
                    s = n(t);
                if (i) {
                    if (s)
                        for (; o < r && !1 !== e.apply(t[o], i); o++);
                    else
                        for (o in t)
                            if (!1 === e.apply(t[o], i)) break
                } else if (s)
                    for (; o < r && !1 !== e.call(t[o], o, t[o]); o++);
                else
                    for (o in t)
                        if (!1 === e.call(t[o], o, t[o])) break;
                return t
            },
            trim: function(t) {
                return null == t ? "" : (t + "").replace(Q, "")
            },
            makeArray: function(t, e) {
                var i = e || [];
                return null != t && (n(Object(t)) ? X.merge(i, "string" == typeof t ? [t] : t) : M.call(i, t)), i
            },
            inArray: function(t, e, n) {
                return null == e ? -1 : W.call(e, t, n)
            },
            merge: function(t, e) {
                for (var n = +e.length, i = 0, o = t.length; i < n; i++) t[o++] = e[i];
                return t.length = o, t
            },
            grep: function(t, e, n) {
                for (var i = [], o = 0, r = t.length, s = !n; o < r; o++) !e(t[o], o) !== s && i.push(t[o]);
                return i
            },
            map: function(t, e, i) {
                var o, r = 0,
                    s = t.length,
                    a = [];
                if (n(t))
                    for (; r < s; r++) null != (o = e(t[r], r, i)) && a.push(o);
                else
                    for (r in t) null != (o = e(t[r], r, i)) && a.push(o);
                return F.apply([], a)
            },
            guid: 1,
            proxy: function(t, e) {
                var n, i, o;
                if ("string" == typeof e && (n = t[e], e = t, t = n), X.isFunction(t)) return i = q.call(arguments, 2), (o = function() {
                    return t.apply(e || this, i.concat(q.call(arguments)))
                }).guid = t.guid = t.guid || X.guid++, o
            },
            now: Date.now,
            support: U
        }), X.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(t, e) {
            B["[object " + e + "]"] = e.toLowerCase()
        });
        var K = function(t) {
            function e(t, e, n, i) {
                var o, r, s, a, c, p, d, f, h, g;
                if ((e ? e.ownerDocument || e : q) !== A && D(e), n = n || [], a = (e = e || A).nodeType, "string" != typeof t || !t || 1 !== a && 9 !== a && 11 !== a) return n;
                if (!i && O) {
                    if (11 !== a && (o = mt.exec(t)))
                        if (s = o[1]) {
                            if (9 === a) {
                                if (!(r = e.getElementById(s)) || !r.parentNode) return n;
                                if (r.id === s) return n.push(r), n
                            } else if (e.ownerDocument && (r = e.ownerDocument.getElementById(s)) && P(e, r) && r.id === s) return n.push(r), n
                        } else {
                            if (o[2]) return G.apply(n, e.getElementsByTagName(t)), n;
                            if ((s = o[3]) && b.getElementsByClassName) return G.apply(n, e.getElementsByClassName(s)), n
                        } if (b.qsa && (!I || !I.test(t))) {
                        if (f = d = H, h = e, g = 1 !== a && t, 1 === a && "object" !== e.nodeName.toLowerCase()) {
                            for (p = C(t), (d = e.getAttribute("id")) ? f = d.replace(yt, "\\$&") : e.setAttribute("id", f), f = "[id='" + f + "'] ", c = p.length; c--;) p[c] = f + u(p[c]);
                            h = vt.test(t) && l(e.parentNode) || e, g = p.join(",")
                        }
                        if (g) try {
                            return G.apply(n, h.querySelectorAll(g)), n
                        } catch (t) {} finally {
                            d || e.removeAttribute("id")
                        }
                    }
                }
                return k(t.replace(st, "$1"), e, n, i)
            }

            function n() {
                var t = [];
                return function e(n, i) {
                    return t.push(n + " ") > x.cacheLength && delete e[t.shift()], e[n + " "] = i
                }
            }

            function i(t) {
                return t[H] = !0, t
            }

            function o(t) {
                var e = A.createElement("div");
                try {
                    return !!t(e)
                } catch (t) {
                    return !1
                } finally {
                    e.parentNode && e.parentNode.removeChild(e), e = null
                }
            }

            function r(t, e) {
                for (var n = t.split("|"), i = t.length; i--;) x.attrHandle[n[i]] = e
            }

            function s(t, e) {
                var n = e && t,
                    i = n && 1 === t.nodeType && 1 === e.nodeType && (~e.sourceIndex || U) - (~t.sourceIndex || U);
                if (i) return i;
                if (n)
                    for (; n = n.nextSibling;)
                        if (n === e) return -1;
                return t ? 1 : -1
            }

            function a(t) {
                return i(function(e) {
                    return e = +e, i(function(n, i) {
                        for (var o, r = t([], n.length, e), s = r.length; s--;) n[o = r[s]] && (n[o] = !(i[o] = n[o]))
                    })
                })
            }

            function l(t) {
                return t && void 0 !== t.getElementsByTagName && t
            }

            function c() {}

            function u(t) {
                for (var e = 0, n = t.length, i = ""; e < n; e++) i += t[e].value;
                return i
            }

            function p(t, e, n) {
                var i = e.dir,
                    o = n && "parentNode" === i,
                    r = M++;
                return e.first ? function(e, n, r) {
                    for (; e = e[i];)
                        if (1 === e.nodeType || o) return t(e, n, r)
                } : function(e, n, s) {
                    var a, l, c = [F, r];
                    if (s) {
                        for (; e = e[i];)
                            if ((1 === e.nodeType || o) && t(e, n, s)) return !0
                    } else
                        for (; e = e[i];)
                            if (1 === e.nodeType || o) {
                                if ((a = (l = e[H] || (e[H] = {}))[i]) && a[0] === F && a[1] === r) return c[2] = a[2];
                                if (l[i] = c, c[2] = t(e, n, s)) return !0
                            }
                }
            }

            function d(t) {
                return t.length > 1 ? function(e, n, i) {
                    for (var o = t.length; o--;)
                        if (!t[o](e, n, i)) return !1;
                    return !0
                } : t[0]
            }

            function f(t, n, i) {
                for (var o = 0, r = n.length; o < r; o++) e(t, n[o], i);
                return i
            }

            function h(t, e, n, i, o) {
                for (var r, s = [], a = 0, l = t.length, c = null != e; a < l; a++)(r = t[a]) && (n && !n(r, i, o) || (s.push(r), c && e.push(a)));
                return s
            }

            function g(t, e, n, o, r, s) {
                return o && !o[H] && (o = g(o)), r && !r[H] && (r = g(r, s)), i(function(i, s, a, l) {
                    var c, u, p, d = [],
                        g = [],
                        m = s.length,
                        v = i || f(e || "*", a.nodeType ? [a] : a, []),
                        y = !t || !i && e ? v : h(v, d, t, a, l),
                        b = n ? r || (i ? t : m || o) ? [] : s : y;
                    if (n && n(y, b, a, l), o)
                        for (c = h(b, g), o(c, [], a, l), u = c.length; u--;)(p = c[u]) && (b[g[u]] = !(y[g[u]] = p));
                    if (i) {
                        if (r || t) {
                            if (r) {
                                for (c = [], u = b.length; u--;)(p = b[u]) && c.push(y[u] = p);
                                r(null, b = [], c, l)
                            }
                            for (u = b.length; u--;)(p = b[u]) && (c = r ? K(i, p) : d[u]) > -1 && (i[c] = !(s[c] = p))
                        }
                    } else b = h(b === s ? b.splice(m, b.length) : b), r ? r(null, s, b, l) : G.apply(s, b)
                })
            }

            function m(t) {
                for (var e, n, i, o = t.length, r = x.relative[t[0].type], s = r || x.relative[" "], a = r ? 1 : 0, l = p(function(t) {
                        return t === e
                    }, s, !0), c = p(function(t) {
                        return K(e, t) > -1
                    }, s, !0), f = [function(t, n, i) {
                        var o = !r && (i || n !== S) || ((e = n).nodeType ? l(t, n, i) : c(t, n, i));
                        return e = null, o
                    }]; a < o; a++)
                    if (n = x.relative[t[a].type]) f = [p(d(f), n)];
                    else {
                        if ((n = x.filter[t[a].type].apply(null, t[a].matches))[H]) {
                            for (i = ++a; i < o && !x.relative[t[i].type]; i++);
                            return g(a > 1 && d(f), a > 1 && u(t.slice(0, a - 1).concat({
                                value: " " === t[a - 2].type ? "*" : ""
                            })).replace(st, "$1"), n, a < i && m(t.slice(a, i)), i < o && m(t = t.slice(i)), i < o && u(t))
                        }
                        f.push(n)
                    } return d(f)
            }

            function v(t, n) {
                var o = n.length > 0,
                    r = t.length > 0,
                    s = function(i, s, a, l, c) {
                        var u, p, d, f = 0,
                            g = "0",
                            m = i && [],
                            v = [],
                            y = S,
                            b = i || r && x.find.TAG("*", c),
                            w = F += null == y ? 1 : Math.random() || .1,
                            T = b.length;
                        for (c && (S = s !== A && s); g !== T && null != (u = b[g]); g++) {
                            if (r && u) {
                                for (p = 0; d = t[p++];)
                                    if (d(u, s, a)) {
                                        l.push(u);
                                        break
                                    } c && (F = w)
                            }
                            o && ((u = !d && u) && f--, i && m.push(u))
                        }
                        if (f += g, o && g !== f) {
                            for (p = 0; d = n[p++];) d(m, v, s, a);
                            if (i) {
                                if (f > 0)
                                    for (; g--;) m[g] || v[g] || (v[g] = Q.call(l));
                                v = h(v)
                            }
                            G.apply(l, v), c && !i && v.length > 0 && f + n.length > 1 && e.uniqueSort(l)
                        }
                        return c && (F = w, S = y), m
                    };
                return o ? i(s) : s
            }
            var y, b, x, w, T, C, E, k, S, $, N, D, A, j, O, I, R, L, P, H = "sizzle" + 1 * new Date,
                q = t.document,
                F = 0,
                M = 0,
                W = n(),
                B = n(),
                z = n(),
                _ = function(t, e) {
                    return t === e && (N = !0), 0
                },
                U = 1 << 31,
                V = {}.hasOwnProperty,
                X = [],
                Q = X.pop,
                Y = X.push,
                G = X.push,
                J = X.slice,
                K = function(t, e) {
                    for (var n = 0, i = t.length; n < i; n++)
                        if (t[n] === e) return n;
                    return -1
                },
                Z = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
                tt = "[\\x20\\t\\r\\n\\f]",
                et = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",
                nt = et.replace("w", "w#"),
                it = "\\[" + tt + "*(" + et + ")(?:" + tt + "*([*^$|!~]?=)" + tt + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + nt + "))|)" + tt + "*\\]",
                ot = ":(" + et + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + it + ")*)|.*)\\)|)",
                rt = new RegExp(tt + "+", "g"),
                st = new RegExp("^" + tt + "+|((?:^|[^\\\\])(?:\\\\.)*)" + tt + "+$", "g"),
                at = new RegExp("^" + tt + "*," + tt + "*"),
                lt = new RegExp("^" + tt + "*([>+~]|" + tt + ")" + tt + "*"),
                ct = new RegExp("=" + tt + "*([^\\]'\"]*?)" + tt + "*\\]", "g"),
                ut = new RegExp(ot),
                pt = new RegExp("^" + nt + "$"),
                dt = {
                    ID: new RegExp("^#(" + et + ")"),
                    CLASS: new RegExp("^\\.(" + et + ")"),
                    TAG: new RegExp("^(" + et.replace("w", "w*") + ")"),
                    ATTR: new RegExp("^" + it),
                    PSEUDO: new RegExp("^" + ot),
                    CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + tt + "*(even|odd|(([+-]|)(\\d*)n|)" + tt + "*(?:([+-]|)" + tt + "*(\\d+)|))" + tt + "*\\)|)", "i"),
                    bool: new RegExp("^(?:" + Z + ")$", "i"),
                    needsContext: new RegExp("^" + tt + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + tt + "*((?:-\\d)?\\d*)" + tt + "*\\)|)(?=[^-]|$)", "i")
                },
                ft = /^(?:input|select|textarea|button)$/i,
                ht = /^h\d$/i,
                gt = /^[^{]+\{\s*\[native \w/,
                mt = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
                vt = /[+~]/,
                yt = /'|\\/g,
                bt = new RegExp("\\\\([\\da-f]{1,6}" + tt + "?|(" + tt + ")|.)", "ig"),
                xt = function(t, e, n) {
                    var i = "0x" + e - 65536;
                    return i != i || n ? e : i < 0 ? String.fromCharCode(i + 65536) : String.fromCharCode(i >> 10 | 55296, 1023 & i | 56320)
                },
                wt = function() {
                    D()
                };
            try {
                G.apply(X = J.call(q.childNodes), q.childNodes), X[q.childNodes.length].nodeType
            } catch (t) {
                G = {
                    apply: X.length ? function(t, e) {
                        Y.apply(t, J.call(e))
                    } : function(t, e) {
                        for (var n = t.length, i = 0; t[n++] = e[i++];);
                        t.length = n - 1
                    }
                }
            }
            for (y in b = e.support = {}, T = e.isXML = function(t) {
                    var e = t && (t.ownerDocument || t).documentElement;
                    return !!e && "HTML" !== e.nodeName
                }, D = e.setDocument = function(t) {
                    var e, n, i = t ? t.ownerDocument || t : q;
                    return i !== A && 9 === i.nodeType && i.documentElement ? (A = i, j = i.documentElement, (n = i.defaultView) && n !== n.top && (n.addEventListener ? n.addEventListener("unload", wt, !1) : n.attachEvent && n.attachEvent("onunload", wt)), O = !T(i), b.attributes = o(function(t) {
                        return t.className = "i", !t.getAttribute("className")
                    }), b.getElementsByTagName = o(function(t) {
                        return t.appendChild(i.createComment("")), !t.getElementsByTagName("*").length
                    }), b.getElementsByClassName = gt.test(i.getElementsByClassName), b.getById = o(function(t) {
                        return j.appendChild(t).id = H, !i.getElementsByName || !i.getElementsByName(H).length
                    }), b.getById ? (x.find.ID = function(t, e) {
                        if (void 0 !== e.getElementById && O) {
                            var n = e.getElementById(t);
                            return n && n.parentNode ? [n] : []
                        }
                    }, x.filter.ID = function(t) {
                        var e = t.replace(bt, xt);
                        return function(t) {
                            return t.getAttribute("id") === e
                        }
                    }) : (delete x.find.ID, x.filter.ID = function(t) {
                        var e = t.replace(bt, xt);
                        return function(t) {
                            var n = void 0 !== t.getAttributeNode && t.getAttributeNode("id");
                            return n && n.value === e
                        }
                    }), x.find.TAG = b.getElementsByTagName ? function(t, e) {
                        return void 0 !== e.getElementsByTagName ? e.getElementsByTagName(t) : b.qsa ? e.querySelectorAll(t) : void 0
                    } : function(t, e) {
                        var n, i = [],
                            o = 0,
                            r = e.getElementsByTagName(t);
                        if ("*" === t) {
                            for (; n = r[o++];) 1 === n.nodeType && i.push(n);
                            return i
                        }
                        return r
                    }, x.find.CLASS = b.getElementsByClassName && function(t, e) {
                        if (O) return e.getElementsByClassName(t)
                    }, R = [], I = [], (b.qsa = gt.test(i.querySelectorAll)) && (o(function(t) {
                        j.appendChild(t).innerHTML = "<a id='" + H + "'></a><select id='" + H + "-\f]' msallowcapture=''><option selected=''></option></select>", t.querySelectorAll("[msallowcapture^='']").length && I.push("[*^$]=" + tt + "*(?:''|\"\")"), t.querySelectorAll("[selected]").length || I.push("\\[" + tt + "*(?:value|" + Z + ")"), t.querySelectorAll("[id~=" + H + "-]").length || I.push("~="), t.querySelectorAll(":checked").length || I.push(":checked"), t.querySelectorAll("a#" + H + "+*").length || I.push(".#.+[+~]")
                    }), o(function(t) {
                        var e = i.createElement("input");
                        e.setAttribute("type", "hidden"), t.appendChild(e).setAttribute("name", "D"), t.querySelectorAll("[name=d]").length && I.push("name" + tt + "*[*^$|!~]?="), t.querySelectorAll(":enabled").length || I.push(":enabled", ":disabled"), t.querySelectorAll("*,:x"), I.push(",.*:")
                    })), (b.matchesSelector = gt.test(L = j.matches || j.webkitMatchesSelector || j.mozMatchesSelector || j.oMatchesSelector || j.msMatchesSelector)) && o(function(t) {
                        b.disconnectedMatch = L.call(t, "div"), L.call(t, "[s!='']:x"), R.push("!=", ot)
                    }), I = I.length && new RegExp(I.join("|")), R = R.length && new RegExp(R.join("|")), e = gt.test(j.compareDocumentPosition), P = e || gt.test(j.contains) ? function(t, e) {
                        var n = 9 === t.nodeType ? t.documentElement : t,
                            i = e && e.parentNode;
                        return t === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : t.compareDocumentPosition && 16 & t.compareDocumentPosition(i)))
                    } : function(t, e) {
                        if (e)
                            for (; e = e.parentNode;)
                                if (e === t) return !0;
                        return !1
                    }, _ = e ? function(t, e) {
                        if (t === e) return N = !0, 0;
                        var n = !t.compareDocumentPosition - !e.compareDocumentPosition;
                        return n || (1 & (n = (t.ownerDocument || t) === (e.ownerDocument || e) ? t.compareDocumentPosition(e) : 1) || !b.sortDetached && e.compareDocumentPosition(t) === n ? t === i || t.ownerDocument === q && P(q, t) ? -1 : e === i || e.ownerDocument === q && P(q, e) ? 1 : $ ? K($, t) - K($, e) : 0 : 4 & n ? -1 : 1)
                    } : function(t, e) {
                        if (t === e) return N = !0, 0;
                        var n, o = 0,
                            r = t.parentNode,
                            a = e.parentNode,
                            l = [t],
                            c = [e];
                        if (!r || !a) return t === i ? -1 : e === i ? 1 : r ? -1 : a ? 1 : $ ? K($, t) - K($, e) : 0;
                        if (r === a) return s(t, e);
                        for (n = t; n = n.parentNode;) l.unshift(n);
                        for (n = e; n = n.parentNode;) c.unshift(n);
                        for (; l[o] === c[o];) o++;
                        return o ? s(l[o], c[o]) : l[o] === q ? -1 : c[o] === q ? 1 : 0
                    }, i) : A
                }, e.matches = function(t, n) {
                    return e(t, null, null, n)
                }, e.matchesSelector = function(t, n) {
                    if ((t.ownerDocument || t) !== A && D(t), n = n.replace(ct, "='$1']"), b.matchesSelector && O && (!R || !R.test(n)) && (!I || !I.test(n))) try {
                        var i = L.call(t, n);
                        if (i || b.disconnectedMatch || t.document && 11 !== t.document.nodeType) return i
                    } catch (t) {}
                    return e(n, A, null, [t]).length > 0
                }, e.contains = function(t, e) {
                    return (t.ownerDocument || t) !== A && D(t), P(t, e)
                }, e.attr = function(t, e) {
                    (t.ownerDocument || t) !== A && D(t);
                    var n = x.attrHandle[e.toLowerCase()],
                        i = n && V.call(x.attrHandle, e.toLowerCase()) ? n(t, e, !O) : void 0;
                    return void 0 !== i ? i : b.attributes || !O ? t.getAttribute(e) : (i = t.getAttributeNode(e)) && i.specified ? i.value : null
                }, e.error = function(t) {
                    throw new Error("Syntax error, unrecognized expression: " + t)
                }, e.uniqueSort = function(t) {
                    var e, n = [],
                        i = 0,
                        o = 0;
                    if (N = !b.detectDuplicates, $ = !b.sortStable && t.slice(0), t.sort(_), N) {
                        for (; e = t[o++];) e === t[o] && (i = n.push(o));
                        for (; i--;) t.splice(n[i], 1)
                    }
                    return $ = null, t
                }, w = e.getText = function(t) {
                    var e, n = "",
                        i = 0,
                        o = t.nodeType;
                    if (o) {
                        if (1 === o || 9 === o || 11 === o) {
                            if ("string" == typeof t.textContent) return t.textContent;
                            for (t = t.firstChild; t; t = t.nextSibling) n += w(t)
                        } else if (3 === o || 4 === o) return t.nodeValue
                    } else
                        for (; e = t[i++];) n += w(e);
                    return n
                }, (x = e.selectors = {
                    cacheLength: 50,
                    createPseudo: i,
                    match: dt,
                    attrHandle: {},
                    find: {},
                    relative: {
                        ">": {
                            dir: "parentNode",
                            first: !0
                        },
                        " ": {
                            dir: "parentNode"
                        },
                        "+": {
                            dir: "previousSibling",
                            first: !0
                        },
                        "~": {
                            dir: "previousSibling"
                        }
                    },
                    preFilter: {
                        ATTR: function(t) {
                            return t[1] = t[1].replace(bt, xt), t[3] = (t[3] || t[4] || t[5] || "").replace(bt, xt), "~=" === t[2] && (t[3] = " " + t[3] + " "), t.slice(0, 4)
                        },
                        CHILD: function(t) {
                            return t[1] = t[1].toLowerCase(), "nth" === t[1].slice(0, 3) ? (t[3] || e.error(t[0]), t[4] = +(t[4] ? t[5] + (t[6] || 1) : 2 * ("even" === t[3] || "odd" === t[3])), t[5] = +(t[7] + t[8] || "odd" === t[3])) : t[3] && e.error(t[0]), t
                        },
                        PSEUDO: function(t) {
                            var e, n = !t[6] && t[2];
                            return dt.CHILD.test(t[0]) ? null : (t[3] ? t[2] = t[4] || t[5] || "" : n && ut.test(n) && (e = C(n, !0)) && (e = n.indexOf(")", n.length - e) - n.length) && (t[0] = t[0].slice(0, e), t[2] = n.slice(0, e)), t.slice(0, 3))
                        }
                    },
                    filter: {
                        TAG: function(t) {
                            var e = t.replace(bt, xt).toLowerCase();
                            return "*" === t ? function() {
                                return !0
                            } : function(t) {
                                return t.nodeName && t.nodeName.toLowerCase() === e
                            }
                        },
                        CLASS: function(t) {
                            var e = W[t + " "];
                            return e || (e = new RegExp("(^|" + tt + ")" + t + "(" + tt + "|$)")) && W(t, function(t) {
                                return e.test("string" == typeof t.className && t.className || void 0 !== t.getAttribute && t.getAttribute("class") || "")
                            })
                        },
                        ATTR: function(t, n, i) {
                            return function(o) {
                                var r = e.attr(o, t);
                                return null == r ? "!=" === n : !n || (r += "", "=" === n ? r === i : "!=" === n ? r !== i : "^=" === n ? i && 0 === r.indexOf(i) : "*=" === n ? i && r.indexOf(i) > -1 : "$=" === n ? i && r.slice(-i.length) === i : "~=" === n ? (" " + r.replace(rt, " ") + " ").indexOf(i) > -1 : "|=" === n && (r === i || r.slice(0, i.length + 1) === i + "-"))
                            }
                        },
                        CHILD: function(t, e, n, i, o) {
                            var r = "nth" !== t.slice(0, 3),
                                s = "last" !== t.slice(-4),
                                a = "of-type" === e;
                            return 1 === i && 0 === o ? function(t) {
                                return !!t.parentNode
                            } : function(e, n, l) {
                                var c, u, p, d, f, h, g = r !== s ? "nextSibling" : "previousSibling",
                                    m = e.parentNode,
                                    v = a && e.nodeName.toLowerCase(),
                                    y = !l && !a;
                                if (m) {
                                    if (r) {
                                        for (; g;) {
                                            for (p = e; p = p[g];)
                                                if (a ? p.nodeName.toLowerCase() === v : 1 === p.nodeType) return !1;
                                            h = g = "only" === t && !h && "nextSibling"
                                        }
                                        return !0
                                    }
                                    if (h = [s ? m.firstChild : m.lastChild], s && y) {
                                        for (f = (c = (u = m[H] || (m[H] = {}))[t] || [])[0] === F && c[1], d = c[0] === F && c[2], p = f && m.childNodes[f]; p = ++f && p && p[g] || (d = f = 0) || h.pop();)
                                            if (1 === p.nodeType && ++d && p === e) {
                                                u[t] = [F, f, d];
                                                break
                                            }
                                    } else if (y && (c = (e[H] || (e[H] = {}))[t]) && c[0] === F) d = c[1];
                                    else
                                        for (;
                                            (p = ++f && p && p[g] || (d = f = 0) || h.pop()) && ((a ? p.nodeName.toLowerCase() !== v : 1 !== p.nodeType) || !++d || (y && ((p[H] || (p[H] = {}))[t] = [F, d]), p !== e)););
                                    return (d -= o) === i || d % i == 0 && d / i >= 0
                                }
                            }
                        },
                        PSEUDO: function(t, n) {
                            var o, r = x.pseudos[t] || x.setFilters[t.toLowerCase()] || e.error("unsupported pseudo: " + t);
                            return r[H] ? r(n) : r.length > 1 ? (o = [t, t, "", n], x.setFilters.hasOwnProperty(t.toLowerCase()) ? i(function(t, e) {
                                for (var i, o = r(t, n), s = o.length; s--;) t[i = K(t, o[s])] = !(e[i] = o[s])
                            }) : function(t) {
                                return r(t, 0, o)
                            }) : r
                        }
                    },
                    pseudos: {
                        not: i(function(t) {
                            var e = [],
                                n = [],
                                o = E(t.replace(st, "$1"));
                            return o[H] ? i(function(t, e, n, i) {
                                for (var r, s = o(t, null, i, []), a = t.length; a--;)(r = s[a]) && (t[a] = !(e[a] = r))
                            }) : function(t, i, r) {
                                return e[0] = t, o(e, null, r, n), e[0] = null, !n.pop()
                            }
                        }),
                        has: i(function(t) {
                            return function(n) {
                                return e(t, n).length > 0
                            }
                        }),
                        contains: i(function(t) {
                            return t = t.replace(bt, xt),
                                function(e) {
                                    return (e.textContent || e.innerText || w(e)).indexOf(t) > -1
                                }
                        }),
                        lang: i(function(t) {
                            return pt.test(t || "") || e.error("unsupported lang: " + t), t = t.replace(bt, xt).toLowerCase(),
                                function(e) {
                                    var n;
                                    do {
                                        if (n = O ? e.lang : e.getAttribute("xml:lang") || e.getAttribute("lang")) return (n = n.toLowerCase()) === t || 0 === n.indexOf(t + "-")
                                    } while ((e = e.parentNode) && 1 === e.nodeType);
                                    return !1
                                }
                        }),
                        target: function(e) {
                            var n = t.location && t.location.hash;
                            return n && n.slice(1) === e.id
                        },
                        root: function(t) {
                            return t === j
                        },
                        focus: function(t) {
                            return t === A.activeElement && (!A.hasFocus || A.hasFocus()) && !!(t.type || t.href || ~t.tabIndex)
                        },
                        enabled: function(t) {
                            return !1 === t.disabled
                        },
                        disabled: function(t) {
                            return !0 === t.disabled
                        },
                        checked: function(t) {
                            var e = t.nodeName.toLowerCase();
                            return "input" === e && !!t.checked || "option" === e && !!t.selected
                        },
                        selected: function(t) {
                            return t.parentNode && t.parentNode.selectedIndex, !0 === t.selected
                        },
                        empty: function(t) {
                            for (t = t.firstChild; t; t = t.nextSibling)
                                if (t.nodeType < 6) return !1;
                            return !0
                        },
                        parent: function(t) {
                            return !x.pseudos.empty(t)
                        },
                        header: function(t) {
                            return ht.test(t.nodeName)
                        },
                        input: function(t) {
                            return ft.test(t.nodeName)
                        },
                        button: function(t) {
                            var e = t.nodeName.toLowerCase();
                            return "input" === e && "button" === t.type || "button" === e
                        },
                        text: function(t) {
                            var e;
                            return "input" === t.nodeName.toLowerCase() && "text" === t.type && (null == (e = t.getAttribute("type")) || "text" === e.toLowerCase())
                        },
                        first: a(function() {
                            return [0]
                        }),
                        last: a(function(t, e) {
                            return [e - 1]
                        }),
                        eq: a(function(t, e, n) {
                            return [n < 0 ? n + e : n]
                        }),
                        even: a(function(t, e) {
                            for (var n = 0; n < e; n += 2) t.push(n);
                            return t
                        }),
                        odd: a(function(t, e) {
                            for (var n = 1; n < e; n += 2) t.push(n);
                            return t
                        }),
                        lt: a(function(t, e, n) {
                            for (var i = n < 0 ? n + e : n; --i >= 0;) t.push(i);
                            return t
                        }),
                        gt: a(function(t, e, n) {
                            for (var i = n < 0 ? n + e : n; ++i < e;) t.push(i);
                            return t
                        })
                    }
                }).pseudos.nth = x.pseudos.eq, {
                    radio: !0,
                    checkbox: !0,
                    file: !0,
                    password: !0,
                    image: !0
                }) x.pseudos[y] = function(t) {
                return function(e) {
                    return "input" === e.nodeName.toLowerCase() && e.type === t
                }
            }(y);
            for (y in {
                    submit: !0,
                    reset: !0
                }) x.pseudos[y] = function(t) {
                return function(e) {
                    var n = e.nodeName.toLowerCase();
                    return ("input" === n || "button" === n) && e.type === t
                }
            }(y);
            return c.prototype = x.filters = x.pseudos, x.setFilters = new c, C = e.tokenize = function(t, n) {
                var i, o, r, s, a, l, c, u = B[t + " "];
                if (u) return n ? 0 : u.slice(0);
                for (a = t, l = [], c = x.preFilter; a;) {
                    for (s in i && !(o = at.exec(a)) || (o && (a = a.slice(o[0].length) || a), l.push(r = [])), i = !1, (o = lt.exec(a)) && (i = o.shift(), r.push({
                            value: i,
                            type: o[0].replace(st, " ")
                        }), a = a.slice(i.length)), x.filter) !(o = dt[s].exec(a)) || c[s] && !(o = c[s](o)) || (i = o.shift(), r.push({
                        value: i,
                        type: s,
                        matches: o
                    }), a = a.slice(i.length));
                    if (!i) break
                }
                return n ? a.length : a ? e.error(t) : B(t, l).slice(0)
            }, E = e.compile = function(t, e) {
                var n, i = [],
                    o = [],
                    r = z[t + " "];
                if (!r) {
                    for (e || (e = C(t)), n = e.length; n--;)(r = m(e[n]))[H] ? i.push(r) : o.push(r);
                    (r = z(t, v(o, i))).selector = t
                }
                return r
            }, k = e.select = function(t, e, n, i) {
                var o, r, s, a, c, p = "function" == typeof t && t,
                    d = !i && C(t = p.selector || t);
                if (n = n || [], 1 === d.length) {
                    if ((r = d[0] = d[0].slice(0)).length > 2 && "ID" === (s = r[0]).type && b.getById && 9 === e.nodeType && O && x.relative[r[1].type]) {
                        if (!(e = (x.find.ID(s.matches[0].replace(bt, xt), e) || [])[0])) return n;
                        p && (e = e.parentNode), t = t.slice(r.shift().value.length)
                    }
                    for (o = dt.needsContext.test(t) ? 0 : r.length; o-- && (s = r[o], !x.relative[a = s.type]);)
                        if ((c = x.find[a]) && (i = c(s.matches[0].replace(bt, xt), vt.test(r[0].type) && l(e.parentNode) || e))) {
                            if (r.splice(o, 1), !(t = i.length && u(r))) return G.apply(n, i), n;
                            break
                        }
                }
                return (p || E(t, d))(i, e, !O, n, vt.test(t) && l(e.parentNode) || e), n
            }, b.sortStable = H.split("").sort(_).join("") === H, b.detectDuplicates = !!N, D(), b.sortDetached = o(function(t) {
                return 1 & t.compareDocumentPosition(A.createElement("div"))
            }), o(function(t) {
                return t.innerHTML = "<a href='#'></a>", "#" === t.firstChild.getAttribute("href")
            }) || r("type|href|height|width", function(t, e, n) {
                if (!n) return t.getAttribute(e, "type" === e.toLowerCase() ? 1 : 2)
            }), b.attributes && o(function(t) {
                return t.innerHTML = "<input/>", t.firstChild.setAttribute("value", ""), "" === t.firstChild.getAttribute("value")
            }) || r("value", function(t, e, n) {
                if (!n && "input" === t.nodeName.toLowerCase()) return t.defaultValue
            }), o(function(t) {
                return null == t.getAttribute("disabled")
            }) || r(Z, function(t, e, n) {
                var i;
                if (!n) return !0 === t[e] ? e.toLowerCase() : (i = t.getAttributeNode(e)) && i.specified ? i.value : null
            }), e
        }(t);
        X.find = K, X.expr = K.selectors, X.expr[":"] = X.expr.pseudos, X.unique = K.uniqueSort, X.text = K.getText, X.isXMLDoc = K.isXML, X.contains = K.contains;
        var Z = X.expr.match.needsContext,
            tt = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
            et = /^.[^:#\[\.,]*$/;
        X.filter = function(t, e, n) {
            var i = e[0];
            return n && (t = ":not(" + t + ")"), 1 === e.length && 1 === i.nodeType ? X.find.matchesSelector(i, t) ? [i] : [] : X.find.matches(t, X.grep(e, function(t) {
                return 1 === t.nodeType
            }))
        }, X.fn.extend({
            find: function(t) {
                var e, n = this.length,
                    i = [],
                    o = this;
                if ("string" != typeof t) return this.pushStack(X(t).filter(function() {
                    for (e = 0; e < n; e++)
                        if (X.contains(o[e], this)) return !0
                }));
                for (e = 0; e < n; e++) X.find(t, o[e], i);
                return (i = this.pushStack(n > 1 ? X.unique(i) : i)).selector = this.selector ? this.selector + " " + t : t, i
            },
            filter: function(t) {
                return this.pushStack(i(this, t || [], !1))
            },
            not: function(t) {
                return this.pushStack(i(this, t || [], !0))
            },
            is: function(t) {
                return !!i(this, "string" == typeof t && Z.test(t) ? X(t) : t || [], !1).length
            }
        });
        var nt, it = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/;
        (X.fn.init = function(t, e) {
            var n, i;
            if (!t) return this;
            if ("string" == typeof t) {
                if (!(n = "<" === t[0] && ">" === t[t.length - 1] && t.length >= 3 ? [null, t, null] : it.exec(t)) || !n[1] && e) return !e || e.jquery ? (e || nt).find(t) : this.constructor(e).find(t);
                if (n[1]) {
                    if (e = e instanceof X ? e[0] : e, X.merge(this, X.parseHTML(n[1], e && e.nodeType ? e.ownerDocument || e : V, !0)), tt.test(n[1]) && X.isPlainObject(e))
                        for (n in e) X.isFunction(this[n]) ? this[n](e[n]) : this.attr(n, e[n]);
                    return this
                }
                return (i = V.getElementById(n[2])) && i.parentNode && (this.length = 1, this[0] = i), this.context = V, this.selector = t, this
            }
            return t.nodeType ? (this.context = this[0] = t, this.length = 1, this) : X.isFunction(t) ? void 0 !== nt.ready ? nt.ready(t) : t(X) : (void 0 !== t.selector && (this.selector = t.selector, this.context = t.context), X.makeArray(t, this))
        }).prototype = X.fn, nt = X(V);
        var ot = /^(?:parents|prev(?:Until|All))/,
            rt = {
                children: !0,
                contents: !0,
                next: !0,
                prev: !0
            };
        X.extend({
            dir: function(t, e, n) {
                for (var i = [], o = void 0 !== n;
                    (t = t[e]) && 9 !== t.nodeType;)
                    if (1 === t.nodeType) {
                        if (o && X(t).is(n)) break;
                        i.push(t)
                    } return i
            },
            sibling: function(t, e) {
                for (var n = []; t; t = t.nextSibling) 1 === t.nodeType && t !== e && n.push(t);
                return n
            }
        }), X.fn.extend({
            has: function(t) {
                var e = X(t, this),
                    n = e.length;
                return this.filter(function() {
                    for (var t = 0; t < n; t++)
                        if (X.contains(this, e[t])) return !0
                })
            },
            closest: function(t, e) {
                for (var n, i = 0, o = this.length, r = [], s = Z.test(t) || "string" != typeof t ? X(t, e || this.context) : 0; i < o; i++)
                    for (n = this[i]; n && n !== e; n = n.parentNode)
                        if (n.nodeType < 11 && (s ? s.index(n) > -1 : 1 === n.nodeType && X.find.matchesSelector(n, t))) {
                            r.push(n);
                            break
                        } return this.pushStack(r.length > 1 ? X.unique(r) : r)
            },
            index: function(t) {
                return t ? "string" == typeof t ? W.call(X(t), this[0]) : W.call(this, t.jquery ? t[0] : t) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
            },
            add: function(t, e) {
                return this.pushStack(X.unique(X.merge(this.get(), X(t, e))))
            },
            addBack: function(t) {
                return this.add(null == t ? this.prevObject : this.prevObject.filter(t))
            }
        }), X.each({
            parent: function(t) {
                var e = t.parentNode;
                return e && 11 !== e.nodeType ? e : null
            },
            parents: function(t) {
                return X.dir(t, "parentNode")
            },
            parentsUntil: function(t, e, n) {
                return X.dir(t, "parentNode", n)
            },
            next: function(t) {
                return o(t, "nextSibling")
            },
            prev: function(t) {
                return o(t, "previousSibling")
            },
            nextAll: function(t) {
                return X.dir(t, "nextSibling")
            },
            prevAll: function(t) {
                return X.dir(t, "previousSibling")
            },
            nextUntil: function(t, e, n) {
                return X.dir(t, "nextSibling", n)
            },
            prevUntil: function(t, e, n) {
                return X.dir(t, "previousSibling", n)
            },
            siblings: function(t) {
                return X.sibling((t.parentNode || {}).firstChild, t)
            },
            children: function(t) {
                return X.sibling(t.firstChild)
            },
            contents: function(t) {
                return t.contentDocument || X.merge([], t.childNodes)
            }
        }, function(t, e) {
            X.fn[t] = function(n, i) {
                var o = X.map(this, e, n);
                return "Until" !== t.slice(-5) && (i = n), i && "string" == typeof i && (o = X.filter(i, o)), this.length > 1 && (rt[t] || X.unique(o), ot.test(t) && o.reverse()), this.pushStack(o)
            }
        });
        var st, at = /\S+/g,
            lt = {};
        X.Callbacks = function(t) {
            t = "string" == typeof t ? lt[t] || function(t) {
                var e = lt[t] = {};
                return X.each(t.match(at) || [], function(t, n) {
                    e[n] = !0
                }), e
            }(t) : X.extend({}, t);
            var e, n, i, o, r, s, a = [],
                l = !t.once && [],
                c = function(p) {
                    for (e = t.memory && p, n = !0, s = o || 0, o = 0, r = a.length, i = !0; a && s < r; s++)
                        if (!1 === a[s].apply(p[0], p[1]) && t.stopOnFalse) {
                            e = !1;
                            break
                        } i = !1, a && (l ? l.length && c(l.shift()) : e ? a = [] : u.disable())
                },
                u = {
                    add: function() {
                        if (a) {
                            var n = a.length;
                            ! function e(n) {
                                X.each(n, function(n, i) {
                                    var o = X.type(i);
                                    "function" === o ? t.unique && u.has(i) || a.push(i) : i && i.length && "string" !== o && e(i)
                                })
                            }(arguments), i ? r = a.length : e && (o = n, c(e))
                        }
                        return this
                    },
                    remove: function() {
                        return a && X.each(arguments, function(t, e) {
                            for (var n;
                                (n = X.inArray(e, a, n)) > -1;) a.splice(n, 1), i && (n <= r && r--, n <= s && s--)
                        }), this
                    },
                    has: function(t) {
                        return t ? X.inArray(t, a) > -1 : !(!a || !a.length)
                    },
                    empty: function() {
                        return a = [], r = 0, this
                    },
                    disable: function() {
                        return a = l = e = void 0, this
                    },
                    disabled: function() {
                        return !a
                    },
                    lock: function() {
                        return l = void 0, e || u.disable(), this
                    },
                    locked: function() {
                        return !l
                    },
                    fireWith: function(t, e) {
                        return !a || n && !l || (e = [t, (e = e || []).slice ? e.slice() : e], i ? l.push(e) : c(e)), this
                    },
                    fire: function() {
                        return u.fireWith(this, arguments), this
                    },
                    fired: function() {
                        return !!n
                    }
                };
            return u
        }, X.extend({
            Deferred: function(t) {
                var e = [
                        ["resolve", "done", X.Callbacks("once memory"), "resolved"],
                        ["reject", "fail", X.Callbacks("once memory"), "rejected"],
                        ["notify", "progress", X.Callbacks("memory")]
                    ],
                    n = "pending",
                    i = {
                        state: function() {
                            return n
                        },
                        always: function() {
                            return o.done(arguments).fail(arguments), this
                        },
                        then: function() {
                            var t = arguments;
                            return X.Deferred(function(n) {
                                X.each(e, function(e, r) {
                                    var s = X.isFunction(t[e]) && t[e];
                                    o[r[1]](function() {
                                        var t = s && s.apply(this, arguments);
                                        t && X.isFunction(t.promise) ? t.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[r[0] + "With"](this === i ? n.promise() : this, s ? [t] : arguments)
                                    })
                                }), t = null
                            }).promise()
                        },
                        promise: function(t) {
                            return null != t ? X.extend(t, i) : i
                        }
                    },
                    o = {};
                return i.pipe = i.then, X.each(e, function(t, r) {
                    var s = r[2],
                        a = r[3];
                    i[r[1]] = s.add, a && s.add(function() {
                        n = a
                    }, e[1 ^ t][2].disable, e[2][2].lock), o[r[0]] = function() {
                        return o[r[0] + "With"](this === o ? i : this, arguments), this
                    }, o[r[0] + "With"] = s.fireWith
                }), i.promise(o), t && t.call(o, o), o
            },
            when: function(t) {
                var e, n, i, o = 0,
                    r = q.call(arguments),
                    s = r.length,
                    a = 1 !== s || t && X.isFunction(t.promise) ? s : 0,
                    l = 1 === a ? t : X.Deferred(),
                    c = function(t, n, i) {
                        return function(o) {
                            n[t] = this, i[t] = arguments.length > 1 ? q.call(arguments) : o, i === e ? l.notifyWith(n, i) : --a || l.resolveWith(n, i)
                        }
                    };
                if (s > 1)
                    for (e = new Array(s), n = new Array(s), i = new Array(s); o < s; o++) r[o] && X.isFunction(r[o].promise) ? r[o].promise().done(c(o, i, r)).fail(l.reject).progress(c(o, n, e)) : --a;
                return a || l.resolveWith(i, r), l.promise()
            }
        }), X.fn.ready = function(t) {
            return X.ready.promise().done(t), this
        }, X.extend({
            isReady: !1,
            readyWait: 1,
            holdReady: function(t) {
                t ? X.readyWait++ : X.ready(!0)
            },
            ready: function(t) {
                (!0 === t ? --X.readyWait : X.isReady) || (X.isReady = !0, !0 !== t && --X.readyWait > 0 || (st.resolveWith(V, [X]), X.fn.triggerHandler && (X(V).triggerHandler("ready"), X(V).off("ready"))))
            }
        }), X.ready.promise = function(e) {
            return st || (st = X.Deferred(), "complete" === V.readyState ? setTimeout(X.ready) : (V.addEventListener("DOMContentLoaded", r, !1), t.addEventListener("load", r, !1))), st.promise(e)
        }, X.ready.promise();
        var ct = X.access = function(t, e, n, i, o, r, s) {
            var a = 0,
                l = t.length,
                c = null == n;
            if ("object" === X.type(n))
                for (a in o = !0, n) X.access(t, e, a, n[a], !0, r, s);
            else if (void 0 !== i && (o = !0, X.isFunction(i) || (s = !0), c && (s ? (e.call(t, i), e = null) : (c = e, e = function(t, e, n) {
                    return c.call(X(t), n)
                })), e))
                for (; a < l; a++) e(t[a], n, s ? i : i.call(t[a], a, e(t[a], n)));
            return o ? t : c ? e.call(t) : l ? e(t[0], n) : r
        };
        X.acceptData = function(t) {
            return 1 === t.nodeType || 9 === t.nodeType || !+t.nodeType
        }, s.uid = 1, s.accepts = X.acceptData, s.prototype = {
            key: function(t) {
                if (!s.accepts(t)) return 0;
                var e = {},
                    n = t[this.expando];
                if (!n) {
                    n = s.uid++;
                    try {
                        e[this.expando] = {
                            value: n
                        }, Object.defineProperties(t, e)
                    } catch (i) {
                        e[this.expando] = n, X.extend(t, e)
                    }
                }
                return this.cache[n] || (this.cache[n] = {}), n
            },
            set: function(t, e, n) {
                var i, o = this.key(t),
                    r = this.cache[o];
                if ("string" == typeof e) r[e] = n;
                else if (X.isEmptyObject(r)) X.extend(this.cache[o], e);
                else
                    for (i in e) r[i] = e[i];
                return r
            },
            get: function(t, e) {
                var n = this.cache[this.key(t)];
                return void 0 === e ? n : n[e]
            },
            access: function(t, e, n) {
                var i;
                return void 0 === e || e && "string" == typeof e && void 0 === n ? void 0 !== (i = this.get(t, e)) ? i : this.get(t, X.camelCase(e)) : (this.set(t, e, n), void 0 !== n ? n : e)
            },
            remove: function(t, e) {
                var n, i, o, r = this.key(t),
                    s = this.cache[r];
                if (void 0 === e) this.cache[r] = {};
                else {
                    X.isArray(e) ? i = e.concat(e.map(X.camelCase)) : (o = X.camelCase(e), e in s ? i = [e, o] : i = (i = o) in s ? [i] : i.match(at) || []), n = i.length;
                    for (; n--;) delete s[i[n]]
                }
            },
            hasData: function(t) {
                return !X.isEmptyObject(this.cache[t[this.expando]] || {})
            },
            discard: function(t) {
                t[this.expando] && delete this.cache[t[this.expando]]
            }
        };
        var ut = new s,
            pt = new s,
            dt = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
            ft = /([A-Z])/g;
        X.extend({
            hasData: function(t) {
                return pt.hasData(t) || ut.hasData(t)
            },
            data: function(t, e, n) {
                return pt.access(t, e, n)
            },
            removeData: function(t, e) {
                pt.remove(t, e)
            },
            _data: function(t, e, n) {
                return ut.access(t, e, n)
            },
            _removeData: function(t, e) {
                ut.remove(t, e)
            }
        }), X.fn.extend({
            data: function(t, e) {
                var n, i, o, r = this[0],
                    s = r && r.attributes;
                if (void 0 === t) {
                    if (this.length && (o = pt.get(r), 1 === r.nodeType && !ut.get(r, "hasDataAttrs"))) {
                        for (n = s.length; n--;) s[n] && (0 === (i = s[n].name).indexOf("data-") && (i = X.camelCase(i.slice(5)), a(r, i, o[i])));
                        ut.set(r, "hasDataAttrs", !0)
                    }
                    return o
                }
                return "object" == typeof t ? this.each(function() {
                    pt.set(this, t)
                }) : ct(this, function(e) {
                    var n, i = X.camelCase(t);
                    if (r && void 0 === e) {
                        if (void 0 !== (n = pt.get(r, t))) return n;
                        if (void 0 !== (n = pt.get(r, i))) return n;
                        if (void 0 !== (n = a(r, i, void 0))) return n
                    } else this.each(function() {
                        var n = pt.get(this, i);
                        pt.set(this, i, e), -1 !== t.indexOf("-") && void 0 !== n && pt.set(this, t, e)
                    })
                }, null, e, arguments.length > 1, null, !0)
            },
            removeData: function(t) {
                return this.each(function() {
                    pt.remove(this, t)
                })
            }
        }), X.extend({
            queue: function(t, e, n) {
                var i;
                if (t) return e = (e || "fx") + "queue", i = ut.get(t, e), n && (!i || X.isArray(n) ? i = ut.access(t, e, X.makeArray(n)) : i.push(n)), i || []
            },
            dequeue: function(t, e) {
                e = e || "fx";
                var n = X.queue(t, e),
                    i = n.length,
                    o = n.shift(),
                    r = X._queueHooks(t, e);
                "inprogress" === o && (o = n.shift(), i--), o && ("fx" === e && n.unshift("inprogress"), delete r.stop, o.call(t, function() {
                    X.dequeue(t, e)
                }, r)), !i && r && r.empty.fire()
            },
            _queueHooks: function(t, e) {
                var n = e + "queueHooks";
                return ut.get(t, n) || ut.access(t, n, {
                    empty: X.Callbacks("once memory").add(function() {
                        ut.remove(t, [e + "queue", n])
                    })
                })
            }
        }), X.fn.extend({
            queue: function(t, e) {
                var n = 2;
                return "string" != typeof t && (e = t, t = "fx", n--), arguments.length < n ? X.queue(this[0], t) : void 0 === e ? this : this.each(function() {
                    var n = X.queue(this, t, e);
                    X._queueHooks(this, t), "fx" === t && "inprogress" !== n[0] && X.dequeue(this, t)
                })
            },
            dequeue: function(t) {
                return this.each(function() {
                    X.dequeue(this, t)
                })
            },
            clearQueue: function(t) {
                return this.queue(t || "fx", [])
            },
            promise: function(t, e) {
                var n, i = 1,
                    o = X.Deferred(),
                    r = this,
                    s = this.length,
                    a = function() {
                        --i || o.resolveWith(r, [r])
                    };
                for ("string" != typeof t && (e = t, t = void 0), t = t || "fx"; s--;)(n = ut.get(r[s], t + "queueHooks")) && n.empty && (i++, n.empty.add(a));
                return a(), o.promise(e)
            }
        });
        var ht = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
            gt = ["Top", "Right", "Bottom", "Left"],
            mt = function(t, e) {
                return t = e || t, "none" === X.css(t, "display") || !X.contains(t.ownerDocument, t)
            },
            vt = /^(?:checkbox|radio)$/i;
        ! function() {
            var t = V.createDocumentFragment().appendChild(V.createElement("div")),
                e = V.createElement("input");
            e.setAttribute("type", "radio"), e.setAttribute("checked", "checked"), e.setAttribute("name", "t"), t.appendChild(e), U.checkClone = t.cloneNode(!0).cloneNode(!0).lastChild.checked, t.innerHTML = "<textarea>x</textarea>", U.noCloneChecked = !!t.cloneNode(!0).lastChild.defaultValue
        }(), U.focusinBubbles = "onfocusin" in t;
        var yt = /^key/,
            bt = /^(?:mouse|pointer|contextmenu)|click/,
            xt = /^(?:focusinfocus|focusoutblur)$/,
            wt = /^([^.]*)(?:\.(.+)|)$/;
        X.event = {
            global: {},
            add: function(t, e, n, i, o) {
                var r, s, a, l, c, u, p, d, f, h, g, m = ut.get(t);
                if (m)
                    for (n.handler && (n = (r = n).handler, o = r.selector), n.guid || (n.guid = X.guid++), (l = m.events) || (l = m.events = {}), (s = m.handle) || (s = m.handle = function(e) {
                            return void 0 !== X && X.event.triggered !== e.type ? X.event.dispatch.apply(t, arguments) : void 0
                        }), c = (e = (e || "").match(at) || [""]).length; c--;) f = g = (a = wt.exec(e[c]) || [])[1], h = (a[2] || "").split(".").sort(), f && (p = X.event.special[f] || {}, f = (o ? p.delegateType : p.bindType) || f, p = X.event.special[f] || {}, u = X.extend({
                        type: f,
                        origType: g,
                        data: i,
                        handler: n,
                        guid: n.guid,
                        selector: o,
                        needsContext: o && X.expr.match.needsContext.test(o),
                        namespace: h.join(".")
                    }, r), (d = l[f]) || ((d = l[f] = []).delegateCount = 0, p.setup && !1 !== p.setup.call(t, i, h, s) || t.addEventListener && t.addEventListener(f, s, !1)), p.add && (p.add.call(t, u), u.handler.guid || (u.handler.guid = n.guid)), o ? d.splice(d.delegateCount++, 0, u) : d.push(u), X.event.global[f] = !0)
            },
            remove: function(t, e, n, i, o) {
                var r, s, a, l, c, u, p, d, f, h, g, m = ut.hasData(t) && ut.get(t);
                if (m && (l = m.events)) {
                    for (c = (e = (e || "").match(at) || [""]).length; c--;)
                        if (f = g = (a = wt.exec(e[c]) || [])[1], h = (a[2] || "").split(".").sort(), f) {
                            for (p = X.event.special[f] || {}, d = l[f = (i ? p.delegateType : p.bindType) || f] || [], a = a[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), s = r = d.length; r--;) u = d[r], !o && g !== u.origType || n && n.guid !== u.guid || a && !a.test(u.namespace) || i && i !== u.selector && ("**" !== i || !u.selector) || (d.splice(r, 1), u.selector && d.delegateCount--, p.remove && p.remove.call(t, u));
                            s && !d.length && (p.teardown && !1 !== p.teardown.call(t, h, m.handle) || X.removeEvent(t, f, m.handle), delete l[f])
                        } else
                            for (f in l) X.event.remove(t, f + e[c], n, i, !0);
                    X.isEmptyObject(l) && (delete m.handle, ut.remove(t, "events"))
                }
            },
            trigger: function(e, n, i, o) {
                var r, s, a, l, c, u, p, d = [i || V],
                    f = _.call(e, "type") ? e.type : e,
                    h = _.call(e, "namespace") ? e.namespace.split(".") : [];
                if (s = a = i = i || V, 3 !== i.nodeType && 8 !== i.nodeType && !xt.test(f + X.event.triggered) && (f.indexOf(".") >= 0 && (h = f.split("."), f = h.shift(), h.sort()), c = f.indexOf(":") < 0 && "on" + f, (e = e[X.expando] ? e : new X.Event(f, "object" == typeof e && e)).isTrigger = o ? 2 : 3, e.namespace = h.join("."), e.namespace_re = e.namespace ? new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, e.result = void 0, e.target || (e.target = i), n = null == n ? [e] : X.makeArray(n, [e]), p = X.event.special[f] || {}, o || !p.trigger || !1 !== p.trigger.apply(i, n))) {
                    if (!o && !p.noBubble && !X.isWindow(i)) {
                        for (l = p.delegateType || f, xt.test(l + f) || (s = s.parentNode); s; s = s.parentNode) d.push(s), a = s;
                        a === (i.ownerDocument || V) && d.push(a.defaultView || a.parentWindow || t)
                    }
                    for (r = 0;
                        (s = d[r++]) && !e.isPropagationStopped();) e.type = r > 1 ? l : p.bindType || f, (u = (ut.get(s, "events") || {})[e.type] && ut.get(s, "handle")) && u.apply(s, n), (u = c && s[c]) && u.apply && X.acceptData(s) && (e.result = u.apply(s, n), !1 === e.result && e.preventDefault());
                    return e.type = f, o || e.isDefaultPrevented() || p._default && !1 !== p._default.apply(d.pop(), n) || !X.acceptData(i) || c && X.isFunction(i[f]) && !X.isWindow(i) && ((a = i[c]) && (i[c] = null), X.event.triggered = f, i[f](), X.event.triggered = void 0, a && (i[c] = a)), e.result
                }
            },
            dispatch: function(t) {
                t = X.event.fix(t);
                var e, n, i, o, r, s = [],
                    a = q.call(arguments),
                    l = (ut.get(this, "events") || {})[t.type] || [],
                    c = X.event.special[t.type] || {};
                if (a[0] = t, t.delegateTarget = this, !c.preDispatch || !1 !== c.preDispatch.call(this, t)) {
                    for (s = X.event.handlers.call(this, t, l), e = 0;
                        (o = s[e++]) && !t.isPropagationStopped();)
                        for (t.currentTarget = o.elem, n = 0;
                            (r = o.handlers[n++]) && !t.isImmediatePropagationStopped();) t.namespace_re && !t.namespace_re.test(r.namespace) || (t.handleObj = r, t.data = r.data, void 0 !== (i = ((X.event.special[r.origType] || {}).handle || r.handler).apply(o.elem, a)) && !1 === (t.result = i) && (t.preventDefault(), t.stopPropagation()));
                    return c.postDispatch && c.postDispatch.call(this, t), t.result
                }
            },
            handlers: function(t, e) {
                var n, i, o, r, s = [],
                    a = e.delegateCount,
                    l = t.target;
                if (a && l.nodeType && (!t.button || "click" !== t.type))
                    for (; l !== this; l = l.parentNode || this)
                        if (!0 !== l.disabled || "click" !== t.type) {
                            for (i = [], n = 0; n < a; n++) void 0 === i[o = (r = e[n]).selector + " "] && (i[o] = r.needsContext ? X(o, this).index(l) >= 0 : X.find(o, this, null, [l]).length), i[o] && i.push(r);
                            i.length && s.push({
                                elem: l,
                                handlers: i
                            })
                        } return a < e.length && s.push({
                    elem: this,
                    handlers: e.slice(a)
                }), s
            },
            props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
            fixHooks: {},
            keyHooks: {
                props: "char charCode key keyCode".split(" "),
                filter: function(t, e) {
                    return null == t.which && (t.which = null != e.charCode ? e.charCode : e.keyCode), t
                }
            },
            mouseHooks: {
                props: "button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
                filter: function(t, e) {
                    var n, i, o, r = e.button;
                    return null == t.pageX && null != e.clientX && (i = (n = t.target.ownerDocument || V).documentElement, o = n.body, t.pageX = e.clientX + (i && i.scrollLeft || o && o.scrollLeft || 0) - (i && i.clientLeft || o && o.clientLeft || 0), t.pageY = e.clientY + (i && i.scrollTop || o && o.scrollTop || 0) - (i && i.clientTop || o && o.clientTop || 0)), t.which || void 0 === r || (t.which = 1 & r ? 1 : 2 & r ? 3 : 4 & r ? 2 : 0), t
                }
            },
            fix: function(t) {
                if (t[X.expando]) return t;
                var e, n, i, o = t.type,
                    r = t,
                    s = this.fixHooks[o];
                for (s || (this.fixHooks[o] = s = bt.test(o) ? this.mouseHooks : yt.test(o) ? this.keyHooks : {}), i = s.props ? this.props.concat(s.props) : this.props, t = new X.Event(r), e = i.length; e--;) t[n = i[e]] = r[n];
                return t.target || (t.target = V), 3 === t.target.nodeType && (t.target = t.target.parentNode), s.filter ? s.filter(t, r) : t
            },
            special: {
                load: {
                    noBubble: !0
                },
                focus: {
                    trigger: function() {
                        if (this !== u() && this.focus) return this.focus(), !1
                    },
                    delegateType: "focusin"
                },
                blur: {
                    trigger: function() {
                        if (this === u() && this.blur) return this.blur(), !1
                    },
                    delegateType: "focusout"
                },
                click: {
                    trigger: function() {
                        if ("checkbox" === this.type && this.click && X.nodeName(this, "input")) return this.click(), !1
                    },
                    _default: function(t) {
                        return X.nodeName(t.target, "a")
                    }
                },
                beforeunload: {
                    postDispatch: function(t) {
                        void 0 !== t.result && t.originalEvent && (t.originalEvent.returnValue = t.result)
                    }
                }
            },
            simulate: function(t, e, n, i) {
                var o = X.extend(new X.Event, n, {
                    type: t,
                    isSimulated: !0,
                    originalEvent: {}
                });
                i ? X.event.trigger(o, null, e) : X.event.dispatch.call(e, o), o.isDefaultPrevented() && n.preventDefault()
            }
        }, X.removeEvent = function(t, e, n) {
            t.removeEventListener && t.removeEventListener(e, n, !1)
        }, X.Event = function(t, e) {
            if (!(this instanceof X.Event)) return new X.Event(t, e);
            t && t.type ? (this.originalEvent = t, this.type = t.type, this.isDefaultPrevented = t.defaultPrevented || void 0 === t.defaultPrevented && !1 === t.returnValue ? l : c) : this.type = t, e && X.extend(this, e), this.timeStamp = t && t.timeStamp || X.now(), this[X.expando] = !0
        }, X.Event.prototype = {
            isDefaultPrevented: c,
            isPropagationStopped: c,
            isImmediatePropagationStopped: c,
            preventDefault: function() {
                var t = this.originalEvent;
                this.isDefaultPrevented = l, t && t.preventDefault && t.preventDefault()
            },
            stopPropagation: function() {
                var t = this.originalEvent;
                this.isPropagationStopped = l, t && t.stopPropagation && t.stopPropagation()
            },
            stopImmediatePropagation: function() {
                var t = this.originalEvent;
                this.isImmediatePropagationStopped = l, t && t.stopImmediatePropagation && t.stopImmediatePropagation(), this.stopPropagation()
            }
        }, X.each({
            mouseenter: "mouseover",
            mouseleave: "mouseout",
            pointerenter: "pointerover",
            pointerleave: "pointerout"
        }, function(t, e) {
            X.event.special[t] = {
                delegateType: e,
                bindType: e,
                handle: function(t) {
                    var n, i = t.relatedTarget,
                        o = t.handleObj;
                    return i && (i === this || X.contains(this, i)) || (t.type = o.origType, n = o.handler.apply(this, arguments), t.type = e), n
                }
            }
        }), U.focusinBubbles || X.each({
            focus: "focusin",
            blur: "focusout"
        }, function(t, e) {
            var n = function(t) {
                X.event.simulate(e, t.target, X.event.fix(t), !0)
            };
            X.event.special[e] = {
                setup: function() {
                    var i = this.ownerDocument || this,
                        o = ut.access(i, e);
                    o || i.addEventListener(t, n, !0), ut.access(i, e, (o || 0) + 1)
                },
                teardown: function() {
                    var i = this.ownerDocument || this,
                        o = ut.access(i, e) - 1;
                    o ? ut.access(i, e, o) : (i.removeEventListener(t, n, !0), ut.remove(i, e))
                }
            }
        }), X.fn.extend({
            on: function(t, e, n, i, o) {
                var r, s;
                if ("object" == typeof t) {
                    for (s in "string" != typeof e && (n = n || e, e = void 0), t) this.on(s, e, n, t[s], o);
                    return this
                }
                if (null == n && null == i ? (i = e, n = e = void 0) : null == i && ("string" == typeof e ? (i = n, n = void 0) : (i = n, n = e, e = void 0)), !1 === i) i = c;
                else if (!i) return this;
                return 1 === o && (r = i, (i = function(t) {
                    return X().off(t), r.apply(this, arguments)
                }).guid = r.guid || (r.guid = X.guid++)), this.each(function() {
                    X.event.add(this, t, i, n, e)
                })
            },
            one: function(t, e, n, i) {
                return this.on(t, e, n, i, 1)
            },
            off: function(t, e, n) {
                var i, o;
                if (t && t.preventDefault && t.handleObj) return i = t.handleObj, X(t.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler), this;
                if ("object" == typeof t) {
                    for (o in t) this.off(o, e, t[o]);
                    return this
                }
                return !1 !== e && "function" != typeof e || (n = e, e = void 0), !1 === n && (n = c), this.each(function() {
                    X.event.remove(this, t, n, e)
                })
            },
            trigger: function(t, e) {
                return this.each(function() {
                    X.event.trigger(t, e, this)
                })
            },
            triggerHandler: function(t, e) {
                var n = this[0];
                if (n) return X.event.trigger(t, e, n, !0)
            }
        });
        var Tt = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
            Ct = /<([\w:]+)/,
            Et = /<|&#?\w+;/,
            kt = /<(?:script|style|link)/i,
            St = /checked\s*(?:[^=]|=\s*.checked.)/i,
            $t = /^$|\/(?:java|ecma)script/i,
            Nt = /^true\/(.*)/,
            Dt = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,
            At = {
                option: [1, "<select multiple='multiple'>", "</select>"],
                thead: [1, "<table>", "</table>"],
                col: [2, "<table><colgroup>", "</colgroup></table>"],
                tr: [2, "<table><tbody>", "</tbody></table>"],
                td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
                _default: [0, "", ""]
            };
        At.optgroup = At.option, At.tbody = At.tfoot = At.colgroup = At.caption = At.thead, At.th = At.td, X.extend({
            clone: function(t, e, n) {
                var i, o, r, s, a = t.cloneNode(!0),
                    l = X.contains(t.ownerDocument, t);
                if (!(U.noCloneChecked || 1 !== t.nodeType && 11 !== t.nodeType || X.isXMLDoc(t)))
                    for (s = m(a), i = 0, o = (r = m(t)).length; i < o; i++) v(r[i], s[i]);
                if (e)
                    if (n)
                        for (r = r || m(t), s = s || m(a), i = 0, o = r.length; i < o; i++) g(r[i], s[i]);
                    else g(t, a);
                return (s = m(a, "script")).length > 0 && h(s, !l && m(t, "script")), a
            },
            buildFragment: function(t, e, n, i) {
                for (var o, r, s, a, l, c, u = e.createDocumentFragment(), p = [], d = 0, f = t.length; d < f; d++)
                    if ((o = t[d]) || 0 === o)
                        if ("object" === X.type(o)) X.merge(p, o.nodeType ? [o] : o);
                        else if (Et.test(o)) {
                    for (r = r || u.appendChild(e.createElement("div")), s = (Ct.exec(o) || ["", ""])[1].toLowerCase(), a = At[s] || At._default, r.innerHTML = a[1] + o.replace(Tt, "<$1></$2>") + a[2], c = a[0]; c--;) r = r.lastChild;
                    X.merge(p, r.childNodes), (r = u.firstChild).textContent = ""
                } else p.push(e.createTextNode(o));
                for (u.textContent = "", d = 0; o = p[d++];)
                    if ((!i || -1 === X.inArray(o, i)) && (l = X.contains(o.ownerDocument, o), r = m(u.appendChild(o), "script"), l && h(r), n))
                        for (c = 0; o = r[c++];) $t.test(o.type || "") && n.push(o);
                return u
            },
            cleanData: function(t) {
                for (var e, n, i, o, r = X.event.special, s = 0; void 0 !== (n = t[s]); s++) {
                    if (X.acceptData(n) && (o = n[ut.expando]) && (e = ut.cache[o])) {
                        if (e.events)
                            for (i in e.events) r[i] ? X.event.remove(n, i) : X.removeEvent(n, i, e.handle);
                        ut.cache[o] && delete ut.cache[o]
                    }
                    delete pt.cache[n[pt.expando]]
                }
            }
        }), X.fn.extend({
            text: function(t) {
                return ct(this, function(t) {
                    return void 0 === t ? X.text(this) : this.empty().each(function() {
                        1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || (this.textContent = t)
                    })
                }, null, t, arguments.length)
            },
            append: function() {
                return this.domManip(arguments, function(t) {
                    1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || p(this, t).appendChild(t)
                })
            },
            prepend: function() {
                return this.domManip(arguments, function(t) {
                    if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                        var e = p(this, t);
                        e.insertBefore(t, e.firstChild)
                    }
                })
            },
            before: function() {
                return this.domManip(arguments, function(t) {
                    this.parentNode && this.parentNode.insertBefore(t, this)
                })
            },
            after: function() {
                return this.domManip(arguments, function(t) {
                    this.parentNode && this.parentNode.insertBefore(t, this.nextSibling)
                })
            },
            remove: function(t, e) {
                for (var n, i = t ? X.filter(t, this) : this, o = 0; null != (n = i[o]); o++) e || 1 !== n.nodeType || X.cleanData(m(n)), n.parentNode && (e && X.contains(n.ownerDocument, n) && h(m(n, "script")), n.parentNode.removeChild(n));
                return this
            },
            empty: function() {
                for (var t, e = 0; null != (t = this[e]); e++) 1 === t.nodeType && (X.cleanData(m(t, !1)), t.textContent = "");
                return this
            },
            clone: function(t, e) {
                return t = null != t && t, e = null == e ? t : e, this.map(function() {
                    return X.clone(this, t, e)
                })
            },
            html: function(t) {
                return ct(this, function(t) {
                    var e = this[0] || {},
                        n = 0,
                        i = this.length;
                    if (void 0 === t && 1 === e.nodeType) return e.innerHTML;
                    if ("string" == typeof t && !kt.test(t) && !At[(Ct.exec(t) || ["", ""])[1].toLowerCase()]) {
                        t = t.replace(Tt, "<$1></$2>");
                        try {
                            for (; n < i; n++) 1 === (e = this[n] || {}).nodeType && (X.cleanData(m(e, !1)), e.innerHTML = t);
                            e = 0
                        } catch (t) {}
                    }
                    e && this.empty().append(t)
                }, null, t, arguments.length)
            },
            replaceWith: function() {
                var t = arguments[0];
                return this.domManip(arguments, function(e) {
                    t = this.parentNode, X.cleanData(m(this)), t && t.replaceChild(e, this)
                }), t && (t.length || t.nodeType) ? this : this.remove()
            },
            detach: function(t) {
                return this.remove(t, !0)
            },
            domManip: function(t, e) {
                t = F.apply([], t);
                var n, i, o, r, s, a, l = 0,
                    c = this.length,
                    u = this,
                    p = c - 1,
                    h = t[0],
                    g = X.isFunction(h);
                if (g || c > 1 && "string" == typeof h && !U.checkClone && St.test(h)) return this.each(function(n) {
                    var i = u.eq(n);
                    g && (t[0] = h.call(this, n, i.html())), i.domManip(t, e)
                });
                if (c && (i = (n = X.buildFragment(t, this[0].ownerDocument, !1, this)).firstChild, 1 === n.childNodes.length && (n = i), i)) {
                    for (r = (o = X.map(m(n, "script"), d)).length; l < c; l++) s = n, l !== p && (s = X.clone(s, !0, !0), r && X.merge(o, m(s, "script"))), e.call(this[l], s, l);
                    if (r)
                        for (a = o[o.length - 1].ownerDocument, X.map(o, f), l = 0; l < r; l++) s = o[l], $t.test(s.type || "") && !ut.access(s, "globalEval") && X.contains(a, s) && (s.src ? X._evalUrl && X._evalUrl(s.src) : X.globalEval(s.textContent.replace(Dt, "")))
                }
                return this
            }
        }), X.each({
            appendTo: "append",
            prependTo: "prepend",
            insertBefore: "before",
            insertAfter: "after",
            replaceAll: "replaceWith"
        }, function(t, e) {
            X.fn[t] = function(t) {
                for (var n, i = [], o = X(t), r = o.length - 1, s = 0; s <= r; s++) n = s === r ? this : this.clone(!0), X(o[s])[e](n), M.apply(i, n.get());
                return this.pushStack(i)
            }
        });
        var jt, Ot = {},
            It = /^margin/,
            Rt = new RegExp("^(" + ht + ")(?!px)[a-z%]+$", "i"),
            Lt = function(e) {
                return e.ownerDocument.defaultView.opener ? e.ownerDocument.defaultView.getComputedStyle(e, null) : t.getComputedStyle(e, null)
            };
        ! function() {
            function e() {
                s.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute", s.innerHTML = "", o.appendChild(r);
                var e = t.getComputedStyle(s, null);
                n = "1%" !== e.top, i = "4px" === e.width, o.removeChild(r)
            }
            var n, i, o = V.documentElement,
                r = V.createElement("div"),
                s = V.createElement("div");
            s.style && (s.style.backgroundClip = "content-box", s.cloneNode(!0).style.backgroundClip = "", U.clearCloneStyle = "content-box" === s.style.backgroundClip, r.style.cssText = "border:0;width:0;height:0;top:0;left:-9999px;margin-top:1px;position:absolute", r.appendChild(s), t.getComputedStyle && X.extend(U, {
                pixelPosition: function() {
                    return e(), n
                },
                boxSizingReliable: function() {
                    return null == i && e(), i
                },
                reliableMarginRight: function() {
                    var e, n = s.appendChild(V.createElement("div"));
                    return n.style.cssText = s.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0", n.style.marginRight = n.style.width = "0", s.style.width = "1px", o.appendChild(r), e = !parseFloat(t.getComputedStyle(n, null).marginRight), o.removeChild(r), s.removeChild(n), e
                }
            }))
        }(), X.swap = function(t, e, n, i) {
            var o, r, s = {};
            for (r in e) s[r] = t.style[r], t.style[r] = e[r];
            for (r in o = n.apply(t, i || []), e) t.style[r] = s[r];
            return o
        };
        var Pt = /^(none|table(?!-c[ea]).+)/,
            Ht = new RegExp("^(" + ht + ")(.*)$", "i"),
            qt = new RegExp("^([+-])=(" + ht + ")", "i"),
            Ft = {
                position: "absolute",
                visibility: "hidden",
                display: "block"
            },
            Mt = {
                letterSpacing: "0",
                fontWeight: "400"
            },
            Wt = ["Webkit", "O", "Moz", "ms"];
        X.extend({
            cssHooks: {
                opacity: {
                    get: function(t, e) {
                        if (e) {
                            var n = x(t, "opacity");
                            return "" === n ? "1" : n
                        }
                    }
                }
            },
            cssNumber: {
                columnCount: !0,
                fillOpacity: !0,
                flexGrow: !0,
                flexShrink: !0,
                fontWeight: !0,
                lineHeight: !0,
                opacity: !0,
                order: !0,
                orphans: !0,
                widows: !0,
                zIndex: !0,
                zoom: !0
            },
            cssProps: {
                float: "cssFloat"
            },
            style: function(t, e, n, i) {
                if (t && 3 !== t.nodeType && 8 !== t.nodeType && t.style) {
                    var o, r, s, a = X.camelCase(e),
                        l = t.style;
                    if (e = X.cssProps[a] || (X.cssProps[a] = T(l, a)), s = X.cssHooks[e] || X.cssHooks[a], void 0 === n) return s && "get" in s && void 0 !== (o = s.get(t, !1, i)) ? o : l[e];
                    "string" === (r = typeof n) && (o = qt.exec(n)) && (n = (o[1] + 1) * o[2] + parseFloat(X.css(t, e)), r = "number"), null != n && n == n && ("number" !== r || X.cssNumber[a] || (n += "px"), U.clearCloneStyle || "" !== n || 0 !== e.indexOf("background") || (l[e] = "inherit"), s && "set" in s && void 0 === (n = s.set(t, n, i)) || (l[e] = n))
                }
            },
            css: function(t, e, n, i) {
                var o, r, s, a = X.camelCase(e);
                return e = X.cssProps[a] || (X.cssProps[a] = T(t.style, a)), (s = X.cssHooks[e] || X.cssHooks[a]) && "get" in s && (o = s.get(t, !0, n)), void 0 === o && (o = x(t, e, i)), "normal" === o && e in Mt && (o = Mt[e]), "" === n || n ? (r = parseFloat(o), !0 === n || X.isNumeric(r) ? r || 0 : o) : o
            }
        }), X.each(["height", "width"], function(t, e) {
            X.cssHooks[e] = {
                get: function(t, n, i) {
                    if (n) return Pt.test(X.css(t, "display")) && 0 === t.offsetWidth ? X.swap(t, Ft, function() {
                        return k(t, e, i)
                    }) : k(t, e, i)
                },
                set: function(t, n, i) {
                    var o = i && Lt(t);
                    return C(0, n, i ? E(t, e, i, "border-box" === X.css(t, "boxSizing", !1, o), o) : 0)
                }
            }
        }), X.cssHooks.marginRight = w(U.reliableMarginRight, function(t, e) {
            if (e) return X.swap(t, {
                display: "inline-block"
            }, x, [t, "marginRight"])
        }), X.each({
            margin: "",
            padding: "",
            border: "Width"
        }, function(t, e) {
            X.cssHooks[t + e] = {
                expand: function(n) {
                    for (var i = 0, o = {}, r = "string" == typeof n ? n.split(" ") : [n]; i < 4; i++) o[t + gt[i] + e] = r[i] || r[i - 2] || r[0];
                    return o
                }
            }, It.test(t) || (X.cssHooks[t + e].set = C)
        }), X.fn.extend({
            css: function(t, e) {
                return ct(this, function(t, e, n) {
                    var i, o, r = {},
                        s = 0;
                    if (X.isArray(e)) {
                        for (i = Lt(t), o = e.length; s < o; s++) r[e[s]] = X.css(t, e[s], !1, i);
                        return r
                    }
                    return void 0 !== n ? X.style(t, e, n) : X.css(t, e)
                }, t, e, arguments.length > 1)
            },
            show: function() {
                return S(this, !0)
            },
            hide: function() {
                return S(this)
            },
            toggle: function(t) {
                return "boolean" == typeof t ? t ? this.show() : this.hide() : this.each(function() {
                    mt(this) ? X(this).show() : X(this).hide()
                })
            }
        }), X.Tween = $, $.prototype = {
            constructor: $,
            init: function(t, e, n, i, o, r) {
                this.elem = t, this.prop = n, this.easing = o || "swing", this.options = e, this.start = this.now = this.cur(), this.end = i, this.unit = r || (X.cssNumber[n] ? "" : "px")
            },
            cur: function() {
                var t = $.propHooks[this.prop];
                return t && t.get ? t.get(this) : $.propHooks._default.get(this)
            },
            run: function(t) {
                var e, n = $.propHooks[this.prop];
                return this.options.duration ? this.pos = e = X.easing[this.easing](t, this.options.duration * t, 0, 1, this.options.duration) : this.pos = e = t, this.now = (this.end - this.start) * e + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : $.propHooks._default.set(this), this
            }
        }, $.prototype.init.prototype = $.prototype, $.propHooks = {
            _default: {
                get: function(t) {
                    var e;
                    return null == t.elem[t.prop] || t.elem.style && null != t.elem.style[t.prop] ? (e = X.css(t.elem, t.prop, "")) && "auto" !== e ? e : 0 : t.elem[t.prop]
                },
                set: function(t) {
                    X.fx.step[t.prop] ? X.fx.step[t.prop](t) : t.elem.style && (null != t.elem.style[X.cssProps[t.prop]] || X.cssHooks[t.prop]) ? X.style(t.elem, t.prop, t.now + t.unit) : t.elem[t.prop] = t.now
                }
            }
        }, $.propHooks.scrollTop = $.propHooks.scrollLeft = {
            set: function(t) {
                t.elem.nodeType && t.elem.parentNode && (t.elem[t.prop] = t.now)
            }
        }, X.easing = {
            linear: function(t) {
                return t
            },
            swing: function(t) {
                return .5 - Math.cos(t * Math.PI) / 2
            }
        }, X.fx = $.prototype.init, X.fx.step = {};
        var Bt, zt, _t = /^(?:toggle|show|hide)$/,
            Ut = new RegExp("^(?:([+-])=|)(" + ht + ")([a-z%]*)$", "i"),
            Vt = /queueHooks$/,
            Xt = [function(t, e, n) {
                var i, o, r, s, a, l, c, u = this,
                    p = {},
                    d = t.style,
                    f = t.nodeType && mt(t),
                    h = ut.get(t, "fxshow");
                for (i in n.queue || (null == (a = X._queueHooks(t, "fx")).unqueued && (a.unqueued = 0, l = a.empty.fire, a.empty.fire = function() {
                        a.unqueued || l()
                    }), a.unqueued++, u.always(function() {
                        u.always(function() {
                            a.unqueued--, X.queue(t, "fx").length || a.empty.fire()
                        })
                    })), 1 === t.nodeType && ("height" in e || "width" in e) && (n.overflow = [d.overflow, d.overflowX, d.overflowY], "inline" === ("none" === (c = X.css(t, "display")) ? ut.get(t, "olddisplay") || b(t.nodeName) : c) && "none" === X.css(t, "float") && (d.display = "inline-block")), n.overflow && (d.overflow = "hidden", u.always(function() {
                        d.overflow = n.overflow[0], d.overflowX = n.overflow[1], d.overflowY = n.overflow[2]
                    })), e)
                    if (o = e[i], _t.exec(o)) {
                        if (delete e[i], r = r || "toggle" === o, o === (f ? "hide" : "show")) {
                            if ("show" !== o || !h || void 0 === h[i]) continue;
                            f = !0
                        }
                        p[i] = h && h[i] || X.style(t, i)
                    } else c = void 0;
                if (X.isEmptyObject(p)) "inline" === ("none" === c ? b(t.nodeName) : c) && (d.display = c);
                else
                    for (i in h ? "hidden" in h && (f = h.hidden) : h = ut.access(t, "fxshow", {}), r && (h.hidden = !f), f ? X(t).show() : u.done(function() {
                            X(t).hide()
                        }), u.done(function() {
                            var e;
                            for (e in ut.remove(t, "fxshow"), p) X.style(t, e, p[e])
                        }), p) s = A(f ? h[i] : 0, i, u), i in h || (h[i] = s.start, f && (s.end = s.start, s.start = "width" === i || "height" === i ? 1 : 0))
            }],
            Qt = {
                "*": [function(t, e) {
                    var n = this.createTween(t, e),
                        i = n.cur(),
                        o = Ut.exec(e),
                        r = o && o[3] || (X.cssNumber[t] ? "" : "px"),
                        s = (X.cssNumber[t] || "px" !== r && +i) && Ut.exec(X.css(n.elem, t)),
                        a = 1,
                        l = 20;
                    if (s && s[3] !== r) {
                        r = r || s[3], o = o || [], s = +i || 1;
                        do {
                            s /= a = a || ".5", X.style(n.elem, t, s + r)
                        } while (a !== (a = n.cur() / i) && 1 !== a && --l)
                    }
                    return o && (s = n.start = +s || +i || 0, n.unit = r, n.end = o[1] ? s + (o[1] + 1) * o[2] : +o[2]), n
                }]
            };
        X.Animation = X.extend(j, {
                tweener: function(t, e) {
                    X.isFunction(t) ? (e = t, t = ["*"]) : t = t.split(" ");
                    for (var n, i = 0, o = t.length; i < o; i++) n = t[i], Qt[n] = Qt[n] || [], Qt[n].unshift(e)
                },
                prefilter: function(t, e) {
                    e ? Xt.unshift(t) : Xt.push(t)
                }
            }), X.speed = function(t, e, n) {
                var i = t && "object" == typeof t ? X.extend({}, t) : {
                    complete: n || !n && e || X.isFunction(t) && t,
                    duration: t,
                    easing: n && e || e && !X.isFunction(e) && e
                };
                return i.duration = X.fx.off ? 0 : "number" == typeof i.duration ? i.duration : i.duration in X.fx.speeds ? X.fx.speeds[i.duration] : X.fx.speeds._default, null != i.queue && !0 !== i.queue || (i.queue = "fx"), i.old = i.complete, i.complete = function() {
                    X.isFunction(i.old) && i.old.call(this), i.queue && X.dequeue(this, i.queue)
                }, i
            }, X.fn.extend({
                fadeTo: function(t, e, n, i) {
                    return this.filter(mt).css("opacity", 0).show().end().animate({
                        opacity: e
                    }, t, n, i)
                },
                animate: function(t, e, n, i) {
                    var o = X.isEmptyObject(t),
                        r = X.speed(e, n, i),
                        s = function() {
                            var e = j(this, X.extend({}, t), r);
                            (o || ut.get(this, "finish")) && e.stop(!0)
                        };
                    return s.finish = s, o || !1 === r.queue ? this.each(s) : this.queue(r.queue, s)
                },
                stop: function(t, e, n) {
                    var i = function(t) {
                        var e = t.stop;
                        delete t.stop, e(n)
                    };
                    return "string" != typeof t && (n = e, e = t, t = void 0), e && !1 !== t && this.queue(t || "fx", []), this.each(function() {
                        var e = !0,
                            o = null != t && t + "queueHooks",
                            r = X.timers,
                            s = ut.get(this);
                        if (o) s[o] && s[o].stop && i(s[o]);
                        else
                            for (o in s) s[o] && s[o].stop && Vt.test(o) && i(s[o]);
                        for (o = r.length; o--;) r[o].elem !== this || null != t && r[o].queue !== t || (r[o].anim.stop(n), e = !1, r.splice(o, 1));
                        !e && n || X.dequeue(this, t)
                    })
                },
                finish: function(t) {
                    return !1 !== t && (t = t || "fx"), this.each(function() {
                        var e, n = ut.get(this),
                            i = n[t + "queue"],
                            o = n[t + "queueHooks"],
                            r = X.timers,
                            s = i ? i.length : 0;
                        for (n.finish = !0, X.queue(this, t, []), o && o.stop && o.stop.call(this, !0), e = r.length; e--;) r[e].elem === this && r[e].queue === t && (r[e].anim.stop(!0), r.splice(e, 1));
                        for (e = 0; e < s; e++) i[e] && i[e].finish && i[e].finish.call(this);
                        delete n.finish
                    })
                }
            }), X.each(["toggle", "show", "hide"], function(t, e) {
                var n = X.fn[e];
                X.fn[e] = function(t, i, o) {
                    return null == t || "boolean" == typeof t ? n.apply(this, arguments) : this.animate(D(e, !0), t, i, o)
                }
            }), X.each({
                slideDown: D("show"),
                slideUp: D("hide"),
                slideToggle: D("toggle"),
                fadeIn: {
                    opacity: "show"
                },
                fadeOut: {
                    opacity: "hide"
                },
                fadeToggle: {
                    opacity: "toggle"
                }
            }, function(t, e) {
                X.fn[t] = function(t, n, i) {
                    return this.animate(e, t, n, i)
                }
            }), X.timers = [], X.fx.tick = function() {
                var t, e = 0,
                    n = X.timers;
                for (Bt = X.now(); e < n.length; e++)(t = n[e])() || n[e] !== t || n.splice(e--, 1);
                n.length || X.fx.stop(), Bt = void 0
            }, X.fx.timer = function(t) {
                X.timers.push(t), t() ? X.fx.start() : X.timers.pop()
            }, X.fx.interval = 13, X.fx.start = function() {
                zt || (zt = setInterval(X.fx.tick, X.fx.interval))
            }, X.fx.stop = function() {
                clearInterval(zt), zt = null
            }, X.fx.speeds = {
                slow: 600,
                fast: 200,
                _default: 400
            }, X.fn.delay = function(t, e) {
                return t = X.fx && X.fx.speeds[t] || t, e = e || "fx", this.queue(e, function(e, n) {
                    var i = setTimeout(e, t);
                    n.stop = function() {
                        clearTimeout(i)
                    }
                })
            },
            function() {
                var t = V.createElement("input"),
                    e = V.createElement("select"),
                    n = e.appendChild(V.createElement("option"));
                t.type = "checkbox", U.checkOn = "" !== t.value, U.optSelected = n.selected, e.disabled = !0, U.optDisabled = !n.disabled, (t = V.createElement("input")).value = "t", t.type = "radio", U.radioValue = "t" === t.value
            }();
        var Yt, Gt = X.expr.attrHandle;
        X.fn.extend({
            attr: function(t, e) {
                return ct(this, X.attr, t, e, arguments.length > 1)
            },
            removeAttr: function(t) {
                return this.each(function() {
                    X.removeAttr(this, t)
                })
            }
        }), X.extend({
            attr: function(t, e, n) {
                var i, o, r = t.nodeType;
                if (t && 3 !== r && 8 !== r && 2 !== r) return void 0 === t.getAttribute ? X.prop(t, e, n) : (1 === r && X.isXMLDoc(t) || (e = e.toLowerCase(), i = X.attrHooks[e] || (X.expr.match.bool.test(e) ? Yt : void 0)), void 0 === n ? i && "get" in i && null !== (o = i.get(t, e)) ? o : null == (o = X.find.attr(t, e)) ? void 0 : o : null !== n ? i && "set" in i && void 0 !== (o = i.set(t, n, e)) ? o : (t.setAttribute(e, n + ""), n) : void X.removeAttr(t, e))
            },
            removeAttr: function(t, e) {
                var n, i, o = 0,
                    r = e && e.match(at);
                if (r && 1 === t.nodeType)
                    for (; n = r[o++];) i = X.propFix[n] || n, X.expr.match.bool.test(n) && (t[i] = !1), t.removeAttribute(n)
            },
            attrHooks: {
                type: {
                    set: function(t, e) {
                        if (!U.radioValue && "radio" === e && X.nodeName(t, "input")) {
                            var n = t.value;
                            return t.setAttribute("type", e), n && (t.value = n), e
                        }
                    }
                }
            }
        }), Yt = {
            set: function(t, e, n) {
                return !1 === e ? X.removeAttr(t, n) : t.setAttribute(n, n), n
            }
        }, X.each(X.expr.match.bool.source.match(/\w+/g), function(t, e) {
            var n = Gt[e] || X.find.attr;
            Gt[e] = function(t, e, i) {
                var o, r;
                return i || (r = Gt[e], Gt[e] = o, o = null != n(t, e, i) ? e.toLowerCase() : null, Gt[e] = r), o
            }
        });
        var Jt = /^(?:input|select|textarea|button)$/i;
        X.fn.extend({
            prop: function(t, e) {
                return ct(this, X.prop, t, e, arguments.length > 1)
            },
            removeProp: function(t) {
                return this.each(function() {
                    delete this[X.propFix[t] || t]
                })
            }
        }), X.extend({
            propFix: {
                for: "htmlFor",
                class: "className"
            },
            prop: function(t, e, n) {
                var i, o, r = t.nodeType;
                if (t && 3 !== r && 8 !== r && 2 !== r) return (1 !== r || !X.isXMLDoc(t)) && (e = X.propFix[e] || e, o = X.propHooks[e]), void 0 !== n ? o && "set" in o && void 0 !== (i = o.set(t, n, e)) ? i : t[e] = n : o && "get" in o && null !== (i = o.get(t, e)) ? i : t[e]
            },
            propHooks: {
                tabIndex: {
                    get: function(t) {
                        return t.hasAttribute("tabindex") || Jt.test(t.nodeName) || t.href ? t.tabIndex : -1
                    }
                }
            }
        }), U.optSelected || (X.propHooks.selected = {
            get: function(t) {
                var e = t.parentNode;
                return e && e.parentNode && e.parentNode.selectedIndex, null
            }
        }), X.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function() {
            X.propFix[this.toLowerCase()] = this
        });
        var Kt = /[\t\r\n\f]/g;
        X.fn.extend({
            addClass: function(t) {
                var e, n, i, o, r, s, a = "string" == typeof t && t,
                    l = 0,
                    c = this.length;
                if (X.isFunction(t)) return this.each(function(e) {
                    X(this).addClass(t.call(this, e, this.className))
                });
                if (a)
                    for (e = (t || "").match(at) || []; l < c; l++)
                        if (i = 1 === (n = this[l]).nodeType && (n.className ? (" " + n.className + " ").replace(Kt, " ") : " ")) {
                            for (r = 0; o = e[r++];) i.indexOf(" " + o + " ") < 0 && (i += o + " ");
                            s = X.trim(i), n.className !== s && (n.className = s)
                        } return this
            },
            removeClass: function(t) {
                var e, n, i, o, r, s, a = 0 === arguments.length || "string" == typeof t && t,
                    l = 0,
                    c = this.length;
                if (X.isFunction(t)) return this.each(function(e) {
                    X(this).removeClass(t.call(this, e, this.className))
                });
                if (a)
                    for (e = (t || "").match(at) || []; l < c; l++)
                        if (i = 1 === (n = this[l]).nodeType && (n.className ? (" " + n.className + " ").replace(Kt, " ") : "")) {
                            for (r = 0; o = e[r++];)
                                for (; i.indexOf(" " + o + " ") >= 0;) i = i.replace(" " + o + " ", " ");
                            s = t ? X.trim(i) : "", n.className !== s && (n.className = s)
                        } return this
            },
            toggleClass: function(t, e) {
                var n = typeof t;
                return "boolean" == typeof e && "string" === n ? e ? this.addClass(t) : this.removeClass(t) : X.isFunction(t) ? this.each(function(n) {
                    X(this).toggleClass(t.call(this, n, this.className, e), e)
                }) : this.each(function() {
                    if ("string" === n)
                        for (var e, i = 0, o = X(this), r = t.match(at) || []; e = r[i++];) o.hasClass(e) ? o.removeClass(e) : o.addClass(e);
                    else "undefined" !== n && "boolean" !== n || (this.className && ut.set(this, "__className__", this.className), this.className = this.className || !1 === t ? "" : ut.get(this, "__className__") || "")
                })
            },
            hasClass: function(t) {
                for (var e = " " + t + " ", n = 0, i = this.length; n < i; n++)
                    if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(Kt, " ").indexOf(e) >= 0) return !0;
                return !1
            }
        });
        var Zt = /\r/g;
        X.fn.extend({
            val: function(t) {
                var e, n, i, o = this[0];
                return arguments.length ? (i = X.isFunction(t), this.each(function(n) {
                    var o;
                    1 === this.nodeType && (null == (o = i ? t.call(this, n, X(this).val()) : t) ? o = "" : "number" == typeof o ? o += "" : X.isArray(o) && (o = X.map(o, function(t) {
                        return null == t ? "" : t + ""
                    })), (e = X.valHooks[this.type] || X.valHooks[this.nodeName.toLowerCase()]) && "set" in e && void 0 !== e.set(this, o, "value") || (this.value = o))
                })) : o ? (e = X.valHooks[o.type] || X.valHooks[o.nodeName.toLowerCase()]) && "get" in e && void 0 !== (n = e.get(o, "value")) ? n : "string" == typeof(n = o.value) ? n.replace(Zt, "") : null == n ? "" : n : void 0
            }
        }), X.extend({
            valHooks: {
                option: {
                    get: function(t) {
                        var e = X.find.attr(t, "value");
                        return null != e ? e : X.trim(X.text(t))
                    }
                },
                select: {
                    get: function(t) {
                        for (var e, n, i = t.options, o = t.selectedIndex, r = "select-one" === t.type || o < 0, s = r ? null : [], a = r ? o + 1 : i.length, l = o < 0 ? a : r ? o : 0; l < a; l++)
                            if (((n = i[l]).selected || l === o) && (U.optDisabled ? !n.disabled : null === n.getAttribute("disabled")) && (!n.parentNode.disabled || !X.nodeName(n.parentNode, "optgroup"))) {
                                if (e = X(n).val(), r) return e;
                                s.push(e)
                            } return s
                    },
                    set: function(t, e) {
                        for (var n, i, o = t.options, r = X.makeArray(e), s = o.length; s--;)((i = o[s]).selected = X.inArray(i.value, r) >= 0) && (n = !0);
                        return n || (t.selectedIndex = -1), r
                    }
                }
            }
        }), X.each(["radio", "checkbox"], function() {
            X.valHooks[this] = {
                set: function(t, e) {
                    if (X.isArray(e)) return t.checked = X.inArray(X(t).val(), e) >= 0
                }
            }, U.checkOn || (X.valHooks[this].get = function(t) {
                return null === t.getAttribute("value") ? "on" : t.value
            })
        }), X.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function(t, e) {
            X.fn[e] = function(t, n) {
                return arguments.length > 0 ? this.on(e, null, t, n) : this.trigger(e)
            }
        }), X.fn.extend({
            hover: function(t, e) {
                return this.mouseenter(t).mouseleave(e || t)
            },
            bind: function(t, e, n) {
                return this.on(t, null, e, n)
            },
            unbind: function(t, e) {
                return this.off(t, null, e)
            },
            delegate: function(t, e, n, i) {
                return this.on(e, t, n, i)
            },
            undelegate: function(t, e, n) {
                return 1 === arguments.length ? this.off(t, "**") : this.off(e, t || "**", n)
            }
        });
        var te = X.now(),
            ee = /\?/;
        X.parseJSON = function(t) {
            return JSON.parse(t + "")
        }, X.parseXML = function(t) {
            var e;
            if (!t || "string" != typeof t) return null;
            try {
                e = (new DOMParser).parseFromString(t, "text/xml")
            } catch (t) {
                e = void 0
            }
            return e && !e.getElementsByTagName("parsererror").length || X.error("Invalid XML: " + t), e
        };
        var ne = /#.*$/,
            ie = /([?&])_=[^&]*/,
            oe = /^(.*?):[ \t]*([^\r\n]*)$/gm,
            re = /^(?:GET|HEAD)$/,
            se = /^\/\//,
            ae = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,
            le = {},
            ce = {},
            ue = "*/".concat("*"),
            pe = t.location.href,
            de = ae.exec(pe.toLowerCase()) || [];
        X.extend({
            active: 0,
            lastModified: {},
            etag: {},
            ajaxSettings: {
                url: pe,
                type: "GET",
                isLocal: /^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(de[1]),
                global: !0,
                processData: !0,
                async: !0,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                accepts: {
                    "*": ue,
                    text: "text/plain",
                    html: "text/html",
                    xml: "application/xml, text/xml",
                    json: "application/json, text/javascript"
                },
                contents: {
                    xml: /xml/,
                    html: /html/,
                    json: /json/
                },
                responseFields: {
                    xml: "responseXML",
                    text: "responseText",
                    json: "responseJSON"
                },
                converters: {
                    "* text": String,
                    "text html": !0,
                    "text json": X.parseJSON,
                    "text xml": X.parseXML
                },
                flatOptions: {
                    url: !0,
                    context: !0
                }
            },
            ajaxSetup: function(t, e) {
                return e ? R(R(t, X.ajaxSettings), e) : R(X.ajaxSettings, t)
            },
            ajaxPrefilter: O(le),
            ajaxTransport: O(ce),
            ajax: function(t, e) {
                function n(t, e, n, s) {
                    var l, u, v, y, x, T = e;
                    2 !== b && (b = 2, a && clearTimeout(a), i = void 0, r = s || "", w.readyState = t > 0 ? 4 : 0, l = t >= 200 && t < 300 || 304 === t, n && (y = function(t, e, n) {
                        for (var i, o, r, s, a = t.contents, l = t.dataTypes;
                            "*" === l[0];) l.shift(), void 0 === i && (i = t.mimeType || e.getResponseHeader("Content-Type"));
                        if (i)
                            for (o in a)
                                if (a[o] && a[o].test(i)) {
                                    l.unshift(o);
                                    break
                                } if (l[0] in n) r = l[0];
                        else {
                            for (o in n) {
                                if (!l[0] || t.converters[o + " " + l[0]]) {
                                    r = o;
                                    break
                                }
                                s || (s = o)
                            }
                            r = r || s
                        }
                        if (r) return r !== l[0] && l.unshift(r), n[r]
                    }(p, w, n)), y = function(t, e, n, i) {
                        var o, r, s, a, l, c = {},
                            u = t.dataTypes.slice();
                        if (u[1])
                            for (s in t.converters) c[s.toLowerCase()] = t.converters[s];
                        for (r = u.shift(); r;)
                            if (t.responseFields[r] && (n[t.responseFields[r]] = e), !l && i && t.dataFilter && (e = t.dataFilter(e, t.dataType)), l = r, r = u.shift())
                                if ("*" === r) r = l;
                                else if ("*" !== l && l !== r) {
                            if (!(s = c[l + " " + r] || c["* " + r]))
                                for (o in c)
                                    if ((a = o.split(" "))[1] === r && (s = c[l + " " + a[0]] || c["* " + a[0]])) {
                                        !0 === s ? s = c[o] : !0 !== c[o] && (r = a[0], u.unshift(a[1]));
                                        break
                                    } if (!0 !== s)
                                if (s && t.throws) e = s(e);
                                else try {
                                    e = s(e)
                                } catch (t) {
                                    return {
                                        state: "parsererror",
                                        error: s ? t : "No conversion from " + l + " to " + r
                                    }
                                }
                        }
                        return {
                            state: "success",
                            data: e
                        }
                    }(p, y, w, l), l ? (p.ifModified && ((x = w.getResponseHeader("Last-Modified")) && (X.lastModified[o] = x), (x = w.getResponseHeader("etag")) && (X.etag[o] = x)), 204 === t || "HEAD" === p.type ? T = "nocontent" : 304 === t ? T = "notmodified" : (T = y.state, u = y.data, l = !(v = y.error))) : (v = T, !t && T || (T = "error", t < 0 && (t = 0))), w.status = t, w.statusText = (e || T) + "", l ? h.resolveWith(d, [u, T, w]) : h.rejectWith(d, [w, T, v]), w.statusCode(m), m = void 0, c && f.trigger(l ? "ajaxSuccess" : "ajaxError", [w, p, l ? u : v]), g.fireWith(d, [w, T]), c && (f.trigger("ajaxComplete", [w, p]), --X.active || X.event.trigger("ajaxStop")))
                }
                "object" == typeof t && (e = t, t = void 0), e = e || {};
                var i, o, r, s, a, l, c, u, p = X.ajaxSetup({}, e),
                    d = p.context || p,
                    f = p.context && (d.nodeType || d.jquery) ? X(d) : X.event,
                    h = X.Deferred(),
                    g = X.Callbacks("once memory"),
                    m = p.statusCode || {},
                    v = {},
                    y = {},
                    b = 0,
                    x = "canceled",
                    w = {
                        readyState: 0,
                        getResponseHeader: function(t) {
                            var e;
                            if (2 === b) {
                                if (!s)
                                    for (s = {}; e = oe.exec(r);) s[e[1].toLowerCase()] = e[2];
                                e = s[t.toLowerCase()]
                            }
                            return null == e ? null : e
                        },
                        getAllResponseHeaders: function() {
                            return 2 === b ? r : null
                        },
                        setRequestHeader: function(t, e) {
                            var n = t.toLowerCase();
                            return b || (t = y[n] = y[n] || t, v[t] = e), this
                        },
                        overrideMimeType: function(t) {
                            return b || (p.mimeType = t), this
                        },
                        statusCode: function(t) {
                            var e;
                            if (t)
                                if (b < 2)
                                    for (e in t) m[e] = [m[e], t[e]];
                                else w.always(t[w.status]);
                            return this
                        },
                        abort: function(t) {
                            var e = t || x;
                            return i && i.abort(e), n(0, e), this
                        }
                    };
                if (h.promise(w).complete = g.add, w.success = w.done, w.error = w.fail, p.url = ((t || p.url || pe) + "").replace(ne, "").replace(se, de[1] + "//"), p.type = e.method || e.type || p.method || p.type, p.dataTypes = X.trim(p.dataType || "*").toLowerCase().match(at) || [""], null == p.crossDomain && (l = ae.exec(p.url.toLowerCase()), p.crossDomain = !(!l || l[1] === de[1] && l[2] === de[2] && (l[3] || ("http:" === l[1] ? "80" : "443")) === (de[3] || ("http:" === de[1] ? "80" : "443")))), p.data && p.processData && "string" != typeof p.data && (p.data = X.param(p.data, p.traditional)), I(le, p, e, w), 2 === b) return w;
                for (u in (c = X.event && p.global) && 0 == X.active++ && X.event.trigger("ajaxStart"), p.type = p.type.toUpperCase(), p.hasContent = !re.test(p.type), o = p.url, p.hasContent || (p.data && (o = p.url += (ee.test(o) ? "&" : "?") + p.data, delete p.data), !1 === p.cache && (p.url = ie.test(o) ? o.replace(ie, "$1_=" + te++) : o + (ee.test(o) ? "&" : "?") + "_=" + te++)), p.ifModified && (X.lastModified[o] && w.setRequestHeader("If-Modified-Since", X.lastModified[o]), X.etag[o] && w.setRequestHeader("If-None-Match", X.etag[o])), (p.data && p.hasContent && !1 !== p.contentType || e.contentType) && w.setRequestHeader("Content-Type", p.contentType), w.setRequestHeader("Accept", p.dataTypes[0] && p.accepts[p.dataTypes[0]] ? p.accepts[p.dataTypes[0]] + ("*" !== p.dataTypes[0] ? ", " + ue + "; q=0.01" : "") : p.accepts["*"]), p.headers) w.setRequestHeader(u, p.headers[u]);
                if (p.beforeSend && (!1 === p.beforeSend.call(d, w, p) || 2 === b)) return w.abort();
                for (u in x = "abort", {
                        success: 1,
                        error: 1,
                        complete: 1
                    }) w[u](p[u]);
                if (i = I(ce, p, e, w)) {
                    w.readyState = 1, c && f.trigger("ajaxSend", [w, p]), p.async && p.timeout > 0 && (a = setTimeout(function() {
                        w.abort("timeout")
                    }, p.timeout));
                    try {
                        b = 1, i.send(v, n)
                    } catch (t) {
                        if (!(b < 2)) throw t;
                        n(-1, t)
                    }
                } else n(-1, "No Transport");
                return w
            },
            getJSON: function(t, e, n) {
                return X.get(t, e, n, "json")
            },
            getScript: function(t, e) {
                return X.get(t, void 0, e, "script")
            }
        }), X.each(["get", "post"], function(t, e) {
            X[e] = function(t, n, i, o) {
                return X.isFunction(n) && (o = o || i, i = n, n = void 0), X.ajax({
                    url: t,
                    type: e,
                    dataType: o,
                    data: n,
                    success: i
                })
            }
        }), X._evalUrl = function(t) {
            return X.ajax({
                url: t,
                type: "GET",
                dataType: "script",
                async: !1,
                global: !1,
                throws: !0
            })
        }, X.fn.extend({
            wrapAll: function(t) {
                var e;
                return X.isFunction(t) ? this.each(function(e) {
                    X(this).wrapAll(t.call(this, e))
                }) : (this[0] && (e = X(t, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && e.insertBefore(this[0]), e.map(function() {
                    for (var t = this; t.firstElementChild;) t = t.firstElementChild;
                    return t
                }).append(this)), this)
            },
            wrapInner: function(t) {
                return X.isFunction(t) ? this.each(function(e) {
                    X(this).wrapInner(t.call(this, e))
                }) : this.each(function() {
                    var e = X(this),
                        n = e.contents();
                    n.length ? n.wrapAll(t) : e.append(t)
                })
            },
            wrap: function(t) {
                var e = X.isFunction(t);
                return this.each(function(n) {
                    X(this).wrapAll(e ? t.call(this, n) : t)
                })
            },
            unwrap: function() {
                return this.parent().each(function() {
                    X.nodeName(this, "body") || X(this).replaceWith(this.childNodes)
                }).end()
            }
        }), X.expr.filters.hidden = function(t) {
            return t.offsetWidth <= 0 && t.offsetHeight <= 0
        }, X.expr.filters.visible = function(t) {
            return !X.expr.filters.hidden(t)
        };
        var fe = /%20/g,
            he = /\[\]$/,
            ge = /\r?\n/g,
            me = /^(?:submit|button|image|reset|file)$/i,
            ve = /^(?:input|select|textarea|keygen)/i;
        X.param = function(t, e) {
            var n, i = [],
                o = function(t, e) {
                    e = X.isFunction(e) ? e() : null == e ? "" : e, i[i.length] = encodeURIComponent(t) + "=" + encodeURIComponent(e)
                };
            if (void 0 === e && (e = X.ajaxSettings && X.ajaxSettings.traditional), X.isArray(t) || t.jquery && !X.isPlainObject(t)) X.each(t, function() {
                o(this.name, this.value)
            });
            else
                for (n in t) L(n, t[n], e, o);
            return i.join("&").replace(fe, "+")
        }, X.fn.extend({
            serialize: function() {
                return X.param(this.serializeArray())
            },
            serializeArray: function() {
                return this.map(function() {
                    var t = X.prop(this, "elements");
                    return t ? X.makeArray(t) : this
                }).filter(function() {
                    var t = this.type;
                    return this.name && !X(this).is(":disabled") && ve.test(this.nodeName) && !me.test(t) && (this.checked || !vt.test(t))
                }).map(function(t, e) {
                    var n = X(this).val();
                    return null == n ? null : X.isArray(n) ? X.map(n, function(t) {
                        return {
                            name: e.name,
                            value: t.replace(ge, "\r\n")
                        }
                    }) : {
                        name: e.name,
                        value: n.replace(ge, "\r\n")
                    }
                }).get()
            }
        }), X.ajaxSettings.xhr = function() {
            try {
                return new XMLHttpRequest
            } catch (t) {}
        };
        var ye = 0,
            be = {},
            xe = {
                0: 200,
                1223: 204
            },
            we = X.ajaxSettings.xhr();
        t.attachEvent && t.attachEvent("onunload", function() {
            for (var t in be) be[t]()
        }), U.cors = !!we && "withCredentials" in we, U.ajax = we = !!we, X.ajaxTransport(function(t) {
            var e;
            if (U.cors || we && !t.crossDomain) return {
                send: function(n, i) {
                    var o, r = t.xhr(),
                        s = ++ye;
                    if (r.open(t.type, t.url, t.async, t.username, t.password), t.xhrFields)
                        for (o in t.xhrFields) r[o] = t.xhrFields[o];
                    for (o in t.mimeType && r.overrideMimeType && r.overrideMimeType(t.mimeType), t.crossDomain || n["X-Requested-With"] || (n["X-Requested-With"] = "XMLHttpRequest"), n) r.setRequestHeader(o, n[o]);
                    e = function(t) {
                        return function() {
                            e && (delete be[s], e = r.onload = r.onerror = null, "abort" === t ? r.abort() : "error" === t ? i(r.status, r.statusText) : i(xe[r.status] || r.status, r.statusText, "string" == typeof r.responseText ? {
                                text: r.responseText
                            } : void 0, r.getAllResponseHeaders()))
                        }
                    }, r.onload = e(), r.onerror = e("error"), e = be[s] = e("abort");
                    try {
                        r.send(t.hasContent && t.data || null)
                    } catch (t) {
                        if (e) throw t
                    }
                },
                abort: function() {
                    e && e()
                }
            }
        }), X.ajaxSetup({
            accepts: {
                script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
            },
            contents: {
                script: /(?:java|ecma)script/
            },
            converters: {
                "text script": function(t) {
                    return X.globalEval(t), t
                }
            }
        }), X.ajaxPrefilter("script", function(t) {
            void 0 === t.cache && (t.cache = !1), t.crossDomain && (t.type = "GET")
        }), X.ajaxTransport("script", function(t) {
            var e, n;
            if (t.crossDomain) return {
                send: function(i, o) {
                    e = X("<script>").prop({
                        async: !0,
                        charset: t.scriptCharset,
                        src: t.url
                    }).on("load error", n = function(t) {
                        e.remove(), n = null, t && o("error" === t.type ? 404 : 200, t.type)
                    }), V.head.appendChild(e[0])
                },
                abort: function() {
                    n && n()
                }
            }
        });
        var Te = [],
            Ce = /(=)\?(?=&|$)|\?\?/;
        X.ajaxSetup({
            jsonp: "callback",
            jsonpCallback: function() {
                var t = Te.pop() || X.expando + "_" + te++;
                return this[t] = !0, t
            }
        }), X.ajaxPrefilter("json jsonp", function(e, n, i) {
            var o, r, s, a = !1 !== e.jsonp && (Ce.test(e.url) ? "url" : "string" == typeof e.data && !(e.contentType || "").indexOf("application/x-www-form-urlencoded") && Ce.test(e.data) && "data");
            if (a || "jsonp" === e.dataTypes[0]) return o = e.jsonpCallback = X.isFunction(e.jsonpCallback) ? e.jsonpCallback() : e.jsonpCallback, a ? e[a] = e[a].replace(Ce, "$1" + o) : !1 !== e.jsonp && (e.url += (ee.test(e.url) ? "&" : "?") + e.jsonp + "=" + o), e.converters["script json"] = function() {
                return s || X.error(o + " was not called"), s[0]
            }, e.dataTypes[0] = "json", r = t[o], t[o] = function() {
                s = arguments
            }, i.always(function() {
                t[o] = r, e[o] && (e.jsonpCallback = n.jsonpCallback, Te.push(o)), s && X.isFunction(r) && r(s[0]), s = r = void 0
            }), "script"
        }), X.parseHTML = function(t, e, n) {
            if (!t || "string" != typeof t) return null;
            "boolean" == typeof e && (n = e, e = !1), e = e || V;
            var i = tt.exec(t),
                o = !n && [];
            return i ? [e.createElement(i[1])] : (i = X.buildFragment([t], e, o), o && o.length && X(o).remove(), X.merge([], i.childNodes))
        };
        var Ee = X.fn.load;
        X.fn.load = function(t, e, n) {
            if ("string" != typeof t && Ee) return Ee.apply(this, arguments);
            var i, o, r, s = this,
                a = t.indexOf(" ");
            return a >= 0 && (i = X.trim(t.slice(a)), t = t.slice(0, a)), X.isFunction(e) ? (n = e, e = void 0) : e && "object" == typeof e && (o = "POST"), s.length > 0 && X.ajax({
                url: t,
                type: o,
                dataType: "html",
                data: e
            }).done(function(t) {
                r = arguments, s.html(i ? X("<div>").append(X.parseHTML(t)).find(i) : t)
            }).complete(n && function(t, e) {
                s.each(n, r || [t.responseText, e, t])
            }), this
        }, X.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function(t, e) {
            X.fn[e] = function(t) {
                return this.on(e, t)
            }
        }), X.expr.filters.animated = function(t) {
            return X.grep(X.timers, function(e) {
                return t === e.elem
            }).length
        };
        var ke = t.document.documentElement;
        X.offset = {
            setOffset: function(t, e, n) {
                var i, o, r, s, a, l, c = X.css(t, "position"),
                    u = X(t),
                    p = {};
                "static" === c && (t.style.position = "relative"), a = u.offset(), r = X.css(t, "top"), l = X.css(t, "left"), ("absolute" === c || "fixed" === c) && (r + l).indexOf("auto") > -1 ? (s = (i = u.position()).top, o = i.left) : (s = parseFloat(r) || 0, o = parseFloat(l) || 0), X.isFunction(e) && (e = e.call(t, n, a)), null != e.top && (p.top = e.top - a.top + s), null != e.left && (p.left = e.left - a.left + o), "using" in e ? e.using.call(t, p) : u.css(p)
            }
        }, X.fn.extend({
            offset: function(t) {
                if (arguments.length) return void 0 === t ? this : this.each(function(e) {
                    X.offset.setOffset(this, t, e)
                });
                var e, n, i = this[0],
                    o = {
                        top: 0,
                        left: 0
                    },
                    r = i && i.ownerDocument;
                return r ? (e = r.documentElement, X.contains(e, i) ? (void 0 !== i.getBoundingClientRect && (o = i.getBoundingClientRect()), n = P(r), {
                    top: o.top + n.pageYOffset - e.clientTop,
                    left: o.left + n.pageXOffset - e.clientLeft
                }) : o) : void 0
            },
            position: function() {
                if (this[0]) {
                    var t, e, n = this[0],
                        i = {
                            top: 0,
                            left: 0
                        };
                    return "fixed" === X.css(n, "position") ? e = n.getBoundingClientRect() : (t = this.offsetParent(), e = this.offset(), X.nodeName(t[0], "html") || (i = t.offset()), i.top += X.css(t[0], "borderTopWidth", !0), i.left += X.css(t[0], "borderLeftWidth", !0)), {
                        top: e.top - i.top - X.css(n, "marginTop", !0),
                        left: e.left - i.left - X.css(n, "marginLeft", !0)
                    }
                }
            },
            offsetParent: function() {
                return this.map(function() {
                    for (var t = this.offsetParent || ke; t && !X.nodeName(t, "html") && "static" === X.css(t, "position");) t = t.offsetParent;
                    return t || ke
                })
            }
        }), X.each({
            scrollLeft: "pageXOffset",
            scrollTop: "pageYOffset"
        }, function(e, n) {
            var i = "pageYOffset" === n;
            X.fn[e] = function(o) {
                return ct(this, function(e, o, r) {
                    var s = P(e);
                    if (void 0 === r) return s ? s[n] : e[o];
                    s ? s.scrollTo(i ? t.pageXOffset : r, i ? r : t.pageYOffset) : e[o] = r
                }, e, o, arguments.length, null)
            }
        }), X.each(["top", "left"], function(t, e) {
            X.cssHooks[e] = w(U.pixelPosition, function(t, n) {
                if (n) return n = x(t, e), Rt.test(n) ? X(t).position()[e] + "px" : n
            })
        }), X.each({
            Height: "height",
            Width: "width"
        }, function(t, e) {
            X.each({
                padding: "inner" + t,
                content: e,
                "": "outer" + t
            }, function(n, i) {
                X.fn[i] = function(i, o) {
                    var r = arguments.length && (n || "boolean" != typeof i),
                        s = n || (!0 === i || !0 === o ? "margin" : "border");
                    return ct(this, function(e, n, i) {
                        var o;
                        return X.isWindow(e) ? e.document.documentElement["client" + t] : 9 === e.nodeType ? (o = e.documentElement, Math.max(e.body["scroll" + t], o["scroll" + t], e.body["offset" + t], o["offset" + t], o["client" + t])) : void 0 === i ? X.css(e, n, s) : X.style(e, n, i, s)
                    }, e, r ? i : void 0, r, null)
                }
            })
        }), X.fn.size = function() {
            return this.length
        }, X.fn.andSelf = X.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function() {
            return X
        });
        var Se = t.jQuery,
            $e = t.$;
        return X.noConflict = function(e) {
            return t.$ === X && (t.$ = $e), e && t.jQuery === X && (t.jQuery = Se), X
        }, void 0 === e && (t.jQuery = t.$ = X), X
    }), window.Modernizr = function(t, e, n) {
        function i(t) {
            g.cssText = t
        }

        function o(t, e) {
            return typeof t === e
        }

        function r(t, e) {
            return !!~("" + t).indexOf(e)
        }

        function s(t, e) {
            for (var i in t) {
                var o = t[i];
                if (!r(o, "-") && g[o] !== n) return "pfx" != e || o
            }
            return !1
        }

        function a(t, e, i) {
            for (var r in t) {
                var s = e[t[r]];
                if (s !== n) return !1 === i ? t[r] : o(s, "function") ? s.bind(i || e) : s
            }
            return !1
        }

        function l(t, e, n) {
            var i = t.charAt(0).toUpperCase() + t.slice(1),
                r = (t + " " + w.join(i + " ") + i).split(" ");
            return o(e, "string") || o(e, "undefined") ? s(r, e) : a(r = (t + " " + T.join(i + " ") + i).split(" "), e, n)
        }
        var c, u, p = {},
            d = e.documentElement,
            f = "modernizr",
            h = e.createElement(f),
            g = h.style,
            m = e.createElement("input"),
            v = ":)",
            y = {}.toString,
            b = " -webkit- -moz- -o- -ms- ".split(" "),
            x = "Webkit Moz O ms",
            w = x.split(" "),
            T = x.toLowerCase().split(" "),
            C = "http://www.w3.org/2000/svg",
            E = {},
            k = {},
            S = {},
            $ = [],
            N = $.slice,
            D = function(t, n, i, o) {
                var r, s, a, l, c = e.createElement("div"),
                    u = e.body,
                    p = u || e.createElement("body");
                if (parseInt(i, 10))
                    for (; i--;)(a = e.createElement("div")).id = o ? o[i] : f + (i + 1), c.appendChild(a);
                //return r = ["&#173;", '<style id="s', f, '">', t, "</style>"].join(""), c.id = f, (u ? c : p).innerHTML += r, p.appendChild(c), u || (p.style.background = "", p.style.overflow = "hidden", l = d.style.overflow, d.style.overflow = "hidden", d.appendChild(p)), s = n(c, t), u ? c.parentNode.removeChild(c) : (p.parentNode.removeChild(p), d.style.overflow = l), !!s
            },
            A = function() {
                var t = {
                    select: "input",
                    change: "input",
                    submit: "form",
                    reset: "form",
                    error: "img",
                    load: "img",
                    abort: "img"
                };
                return function(i, r) {
                    r = r || e.createElement(t[i] || "div");
                    var s = (i = "on" + i) in r;
                    return s || (r.setAttribute || (r = e.createElement("div")), r.setAttribute && r.removeAttribute && (r.setAttribute(i, ""), s = o(r[i], "function"), o(r[i], "undefined") || (r[i] = n), r.removeAttribute(i))), r = null, s
                }
            }(),
            j = {}.hasOwnProperty;
        for (var O in u = o(j, "undefined") || o(j.call, "undefined") ? function(t, e) {
                return e in t && o(t.constructor.prototype[e], "undefined")
            } : function(t, e) {
                return j.call(t, e)
            }, Function.prototype.bind || (Function.prototype.bind = function(t) {
                var e = this;
                if ("function" != typeof e) throw new TypeError;
                var n = N.call(arguments, 1),
                    i = function() {
                        if (this instanceof i) {
                            var o = function() {};
                            o.prototype = e.prototype;
                            var r = new o,
                                s = e.apply(r, n.concat(N.call(arguments)));
                            return Object(s) === s ? s : r
                        }
                        return e.apply(t, n.concat(N.call(arguments)))
                    };
                return i
            }), E.flexbox = function() {
                return l("flexWrap")
            }, E.canvas = function() {
                var t = e.createElement("canvas");
                return !!t.getContext && !!t.getContext("2d")
            }, E.canvastext = function() {
                return !!p.canvas && !!o(e.createElement("canvas").getContext("2d").fillText, "function")
            }, E.webgl = function() {
                return !!t.WebGLRenderingContext
            }, E.touch = function() {
                var n;
                return "ontouchstart" in t || t.DocumentTouch && e instanceof DocumentTouch ? n = !0 : D(["@media (", b.join("touch-enabled),("), f, ")", "{#modernizr{top:9px;position:absolute}}"].join(""), function(t) {
                    n = 9 === t.offsetTop
                }), n
            }, E.geolocation = function() {
                return "geolocation" in navigator
            }, E.postmessage = function() {
                return !!t.postMessage
            }, E.websqldatabase = function() {
                return !!t.openDatabase
            }, E.indexedDB = function() {
                return !!l("indexedDB", t)
            }, E.hashchange = function() {
                return A("hashchange", t) && (e.documentMode === n || e.documentMode > 7)
            }, E.history = function() {
                return !!t.history && !!history.pushState
            }, E.draganddrop = function() {
                var t = e.createElement("div");
                return "draggable" in t || "ondragstart" in t && "ondrop" in t
            }, E.websockets = function() {
                return "WebSocket" in t || "MozWebSocket" in t
            }, E.rgba = function() {
                return i("background-color:rgba(150,255,150,.5)"), r(g.backgroundColor, "rgba")
            }, E.hsla = function() {
                return i("background-color:hsla(120,40%,100%,.5)"), r(g.backgroundColor, "rgba") || r(g.backgroundColor, "hsla")
            }, E.multiplebgs = function() {
                return i("background:url(https://),url(https://),red url(https://)"), /(url\s*\(.*?){3}/.test(g.background)
            }, E.backgroundsize = function() {
                return l("backgroundSize")
            }, E.borderimage = function() {
                return l("borderImage")
            }, E.borderradius = function() {
                return l("borderRadius")
            }, E.boxshadow = function() {
                return l("boxShadow")
            }, E.textshadow = function() {
                return "" === e.createElement("div").style.textShadow
            }, E.opacity = function() {
                return t = "opacity:.55", i(b.join(t + ";") + (e || "")), /^0.55$/.test(g.opacity);
                var t, e
            }, E.cssanimations = function() {
                return l("animationName")
            }, E.csscolumns = function() {
                return l("columnCount")
            }, E.cssgradients = function() {
                var t = "background-image:";
                return i((t + "-webkit- ".split(" ").join("gradient(linear,left top,right bottom,from(#9f9),to(white));" + t) + b.join("linear-gradient(left top,#9f9, white);" + t)).slice(0, -t.length)), r(g.backgroundImage, "gradient")
            }, E.cssreflections = function() {
                return l("boxReflect")
            }, E.csstransforms = function() {
                return !!l("transform")
            }, E.csstransforms3d = function() {
                var t = !!l("perspective");
                return t && "webkitPerspective" in d.style && D("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}", function(e, n) {
                    t = 9 === e.offsetLeft && 3 === e.offsetHeight
                }), t
            }, E.csstransitions = function() {
                return l("transition")
            }, E.fontface = function() {
                var t;
                return D('@font-face {font-family:"font";src:url("https://")}', function(n, i) {
                    var o = e.getElementById("smodernizr"),
                        r = o.sheet || o.styleSheet,
                        s = r ? r.cssRules && r.cssRules[0] ? r.cssRules[0].cssText : r.cssText || "" : "";
                    t = /src/i.test(s) && 0 === s.indexOf(i.split(" ")[0])
                }), t
            }, E.generatedcontent = function() {
                var t;
                return D(["#", f, "{font:0/0 a}#", f, ':after{content:"', v, '";visibility:hidden;font:3px/1 a}'].join(""), function(e) {
                    t = e.offsetHeight >= 3
                }), t
            }, E.video = function() {
                var t = e.createElement("video"),
                    n = !1;
                try {
                    (n = !!t.canPlayType) && ((n = new Boolean(n)).ogg = t.canPlayType('video/ogg; codecs="theora"').replace(/^no$/, ""), n.h264 = t.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/, ""), n.webm = t.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/, ""))
                } catch (t) {}
                return n
            }, E.audio = function() {
                var t = e.createElement("audio"),
                    n = !1;
                try {
                    (n = !!t.canPlayType) && ((n = new Boolean(n)).ogg = t.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/, ""), n.mp3 = t.canPlayType("audio/mpeg;").replace(/^no$/, ""), n.wav = t.canPlayType('audio/wav; codecs="1"').replace(/^no$/, ""), n.m4a = (t.canPlayType("audio/x-m4a;") || t.canPlayType("audio/aac;")).replace(/^no$/, ""))
                } catch (t) {}
                return n
            }, E.localstorage = function() {
                try {
                    return localStorage.setItem(f, f), localStorage.removeItem(f), !0
                } catch (t) {
                    return !1
                }
            }, E.sessionstorage = function() {
                try {
                    return sessionStorage.setItem(f, f), sessionStorage.removeItem(f), !0
                } catch (t) {
                    return !1
                }
            }, E.webworkers = function() {
                return !!t.Worker
            }, E.applicationcache = function() {
                return !!t.applicationCache
            }, E.svg = function() {
                return !!e.createElementNS && !!e.createElementNS(C, "svg").createSVGRect
            }, E.inlinesvg = function() {
                var t = e.createElement("div");
                return t.innerHTML = "<svg/>", (t.firstChild && t.firstChild.namespaceURI) == C
            }, E.smil = function() {
                return !!e.createElementNS && /SVGAnimate/.test(y.call(e.createElementNS(C, "animate")))
            }, E.svgclippaths = function() {
                return !!e.createElementNS && /SVGClipPath/.test(y.call(e.createElementNS(C, "clipPath")))
            }, E) u(E, O) && (c = O.toLowerCase(), p[c] = E[O](), $.push((p[c] ? "" : "no-") + c));
        return p.input || (p.input = function(n) {
                for (var i = 0, o = n.length; i < o; i++) S[n[i]] = n[i] in m;
                return S.list && (S.list = !!e.createElement("datalist") && !!t.HTMLDataListElement), S
            }("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")), p.inputtypes = function(t) {
                for (var i, o, r, s = 0, a = t.length; s < a; s++) m.setAttribute("type", o = t[s]), (i = "text" !== m.type) && (m.value = v, m.style.cssText = "position:absolute;visibility:hidden;", /^range$/.test(o) && m.style.WebkitAppearance !== n ? (d.appendChild(m), i = (r = e.defaultView).getComputedStyle && "textfield" !== r.getComputedStyle(m, null).WebkitAppearance && 0 !== m.offsetHeight, d.removeChild(m)) : /^(search|tel)$/.test(o) || (i = /^(url|email)$/.test(o) ? m.checkValidity && !1 === m.checkValidity() : m.value != v)), k[t[s]] = !!i;
                return k
            }("search tel url email datetime date month week time datetime-local number range color".split(" "))), p.addTest = function(t, e) {
                if ("object" == typeof t)
                    for (var i in t) u(t, i) && p.addTest(i, t[i]);
                else {
                    if (t = t.toLowerCase(), p[t] !== n) return p;
                    e = "function" == typeof e ? e() : e, d.className += " " + (e ? "" : "no-") + t, p[t] = e
                }
                return p
            }, i(""), h = m = null,
            function(t, e) {
                function n() {
                    var t = g.elements;
                    return "string" == typeof t ? t.split(" ") : t
                }

                function i(t) {
                    var e = h[t[d]];
                    return e || (e = {}, f++, t[d] = f, h[f] = e), e
                }

                function o(t, n, o) {
                    return n || (n = e), l ? n.createElement(t) : (o || (o = i(n)), !(r = o.cache[t] ? o.cache[t].cloneNode() : p.test(t) ? (o.cache[t] = o.createElem(t)).cloneNode() : o.createElem(t)).canHaveChildren || u.test(t) || r.tagUrn ? r : o.frag.appendChild(r));
                    var r
                }

                function r(t, e) {
                    e.cache || (e.cache = {}, e.createElem = t.createElement, e.createFrag = t.createDocumentFragment, e.frag = e.createFrag()), t.createElement = function(n) {
                        return g.shivMethods ? o(n, t, e) : e.createElem(n)
                    }, t.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + n().join().replace(/[\w\-]+/g, function(t) {
                        return e.createElem(t), e.frag.createElement(t), 'c("' + t + '")'
                    }) + ");return n}")(g, e.frag)
                }

                function s(t) {
                    t || (t = e);
                    var n = i(t);
                    return g.shivCSS && !a && !n.hasCSS && (n.hasCSS = !! function(t, e) {
                        var n = t.createElement("p"),
                            i = t.getElementsByTagName("head")[0] || t.documentElement;
                        return n.innerHTML = "x<style nonce='8f0882ce3be14f201cadd0eff5726cbd'>" + e + "</style>", i.insertBefore(n.lastChild, i.firstChild)
                    }(t, "article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")), l || r(t, n), t
                }
                var a, l, c = t.html5 || {},
                    u = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,
                    p = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,
                    d = "_html5shiv",
                    f = 0,
                    h = {};
                ! function() {
                    try {
                        var t = e.createElement("a");
                        t.innerHTML = "<xyz></xyz>", a = "hidden" in t, l = 1 == t.childNodes.length || function() {
                            e.createElement("a");
                            var t = e.createDocumentFragment();
                            return void 0 === t.cloneNode || void 0 === t.createDocumentFragment || void 0 === t.createElement
                        }()
                    } catch (t) {
                        a = !0, l = !0
                    }
                }();
                var g = {
                    elements: c.elements || "abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output progress section summary template time video",
                    version: "3.7.0",
                    shivCSS: !1 !== c.shivCSS,
                    supportsUnknownElements: l,
                    shivMethods: !1 !== c.shivMethods,
                    type: "default",
                    shivDocument: s,
                    createElement: o,
                    createDocumentFragment: function(t, o) {
                        if (t || (t = e), l) return t.createDocumentFragment();
                        for (var r = (o = o || i(t)).frag.cloneNode(), s = 0, a = n(), c = a.length; s < c; s++) r.createElement(a[s]);
                        return r
                    }
                };
                t.html5 = g, s(e)
            }(this, e), p._version = "2.8.3", p._prefixes = b, p._domPrefixes = T, p._cssomPrefixes = w, p.mq = function(e) {
                var n, i = t.matchMedia || t.msMatchMedia;
                return i ? i(e) && i(e).matches || !1 : (D("@media " + e + " { #" + f + " { position: absolute; } }", function(e) {
                    n = "absolute" == (t.getComputedStyle ? getComputedStyle(e, null) : e.currentStyle).position
                }), n)
            }, p.hasEvent = A, p.testProp = function(t) {
                return s([t])
            }, p.testAllProps = l, p.testStyles = D, p.prefixed = function(t, e, n) {
                return e ? l(t, e, n) : l(t, "pfx")
            }, d.className = d.className.replace(/(^|\s)no-js(\s|$)/, "$1$2") + " js " + $.join(" "), p
    }(this, this.document), function(t, e, n) {
        function i(t) {
            return "[object Function]" == m.call(t)
        }

        function o(t) {
            return "string" == typeof t
        }

        function r() {}

        function s(t) {
            return !t || "loaded" == t || "complete" == t || "uninitialized" == t
        }

        function a() {
            var t = v.shift();
            y = 1, t ? t.t ? h(function() {
                ("c" == t.t ? d.injectCss : d.injectJs)(t.s, 0, t.a, t.x, t.e, 1)
            }, 0) : (t(), a()) : y = 0
        }

        function l(t, n, i, o, r, l, c) {
            function u(e) {
                if (!f && s(p.readyState) && (b.r = f = 1, !y && a(), p.onload = p.onreadystatechange = null, e))
                    for (var i in "img" != t && h(function() {
                            w.removeChild(p)
                        }, 50), S[n]) S[n].hasOwnProperty(i) && S[n][i].onload()
            }
            c = c || d.errorTimeout;
            var p = e.createElement(t),
                f = 0,
                m = 0,
                b = {
                    t: i,
                    s: n,
                    e: r,
                    a: l,
                    x: c
                };
            1 === S[n] && (m = 1, S[n] = []), "object" == t ? p.data = n : (p.src = n, p.type = t), p.width = p.height = "0", p.onerror = p.onload = p.onreadystatechange = function() {
                u.call(this, m)
            }, v.splice(o, 0, b), "img" != t && (m || 2 === S[n] ? (w.insertBefore(p, x ? null : g), h(u, c)) : S[n].push(p))
        }

        function c(t, e, n, i, r) {
            return y = 0, e = e || "j", o(t) ? l("c" == e ? C : T, t, e, this.i++, n, i, r) : (v.splice(this.i++, 0, t), 1 == v.length && a()), this
        }

        function u() {
            var t = d;
            return t.loader = {
                load: c,
                i: 0
            }, t
        }
        var p, d, f = e.documentElement,
            h = t.setTimeout,
            g = e.getElementsByTagName("script")[0],
            m = {}.toString,
            v = [],
            y = 0,
            b = "MozAppearance" in f.style,
            x = b && !!e.createRange().compareNode,
            w = x ? f : g.parentNode,
            T = (f = t.opera && "[object Opera]" == m.call(t.opera), f = !!e.attachEvent && !f, b ? "object" : f ? "script" : "img"),
            C = f ? "script" : T,
            E = Array.isArray || function(t) {
                return "[object Array]" == m.call(t)
            },
            k = [],
            S = {},
            $ = {
                timeout: function(t, e) {
                    return e.length && (t.timeout = e[0]), t
                }
            };
        (d = function(t) {
            function e(t, e, o, r, s) {
                var a = function(t) {
                        t = t.split("!");
                        var e, n, i, o = k.length,
                            r = t.pop(),
                            s = t.length;
                        for (r = {
                                url: r,
                                origUrl: r,
                                prefixes: t
                            }, n = 0; n < s; n++) i = t[n].split("="), (e = $[i.shift()]) && (r = e(r, i));
                        for (n = 0; n < o; n++) r = k[n](r);
                        return r
                    }(t),
                    l = a.autoCallback;
                a.url.split(".").pop().split("?").shift(), a.bypass || (e && (e = i(e) ? e : e[t] || e[r] || e[t.split("/").pop().split("?")[0]]), a.instead ? a.instead(t, e, o, r, s) : (S[a.url] ? a.noexec = !0 : S[a.url] = 1, o.load(a.url, a.forceCSS || !a.forceJS && "css" == a.url.split(".").pop().split("?").shift() ? "c" : n, a.noexec, a.attrs, a.timeout), (i(e) || i(l)) && o.load(function() {
                    u(), e && e(a.origUrl, s, r), l && l(a.origUrl, s, r), S[a.url] = 2
                })))
            }

            function s(t, n) {
                function s(t, r) {
                    if (t) {
                        if (o(t)) r || (p = function() {
                            var t = [].slice.call(arguments);
                            d.apply(this, t), f()
                        }), e(t, p, n, 0, c);
                        else if (Object(t) === t)
                            for (l in a = function() {
                                    var e, n = 0;
                                    for (e in t) t.hasOwnProperty(e) && n++;
                                    return n
                                }(), t) t.hasOwnProperty(l) && (!r && !--a && (i(p) ? p = function() {
                                var t = [].slice.call(arguments);
                                d.apply(this, t), f()
                            } : p[l] = function(t) {
                                return function() {
                                    var e = [].slice.call(arguments);
                                    t && t.apply(this, e), f()
                                }
                            }(d[l])), e(t[l], p, n, l, c))
                    } else !r && f()
                }
                var a, l, c = !!t.test,
                    u = t.load || t.both,
                    p = t.callback || r,
                    d = p,
                    f = t.complete || r;
                s(c ? t.yep : t.nope, !!u), u && s(u)
            }
            var a, l, c = this.yepnope.loader;
            if (o(t)) e(t, 0, c, 0);
            else if (E(t))
                for (a = 0; a < t.length; a++) o(l = t[a]) ? e(l, 0, c, 0) : E(l) ? d(l) : Object(l) === l && s(l, c);
            else Object(t) === t && s(t, c)
        }).addPrefix = function(t, e) {
            $[t] = e
        }, d.addFilter = function(t) {
            k.push(t)
        }, d.errorTimeout = 1e4, null == e.readyState && e.addEventListener && (e.readyState = "loading", e.addEventListener("DOMContentLoaded", p = function() {
            e.removeEventListener("DOMContentLoaded", p, 0), e.readyState = "complete"
        }, 0)), t.yepnope = u(), t.yepnope.executeStack = a, t.yepnope.injectJs = function(t, n, i, o, l, c) {
            var u, p, f = e.createElement("script");
            o = o || d.errorTimeout;
            for (p in f.src = t, i) f.setAttribute(p, i[p]);
            n = c ? a : n || r, f.onreadystatechange = f.onload = function() {
                !u && s(f.readyState) && (u = 1, n(), f.onload = f.onreadystatechange = null)
            }, h(function() {
                u || (u = 1, n(1))
            }, o), l ? f.onload() : g.parentNode.insertBefore(f, g)
        }, t.yepnope.injectCss = function(t, n, i, o, s, l) {
            var c;
            o = e.createElement("link"), n = l ? a : n || r;
            for (c in o.href = t, o.rel = "stylesheet", o.type = "text/css", i) o.setAttribute(c, i[c]);
            s || (g.parentNode.insertBefore(o, g), h(n, 0))
        }
    }(this, document), Modernizr.load = function() {
        yepnope.apply(window, [].slice.call(arguments, 0))
    }, function(t) {
        "use strict";
        t.matchMedia = t.matchMedia || function(t) {
            var e, n = t.documentElement,
                i = n.firstElementChild || n.firstChild,
                o = t.createElement("body"),
                r = t.createElement("div");
            return r.id = "mq-test-1", r.style.cssText = "position:absolute;top:-100em", o.style.background = "none", o.appendChild(r),
                function(t) {
                    return r.innerHTML = '&shy;<style media="' + t + '"> #mq-test-1 { width: 42px; }</style>', n.insertBefore(o, i), e = 42 === r.offsetWidth, n.removeChild(o), {
                        matches: e,
                        media: t
                    }
                }
        }(t.document)
    }(this), function(t) {
        "use strict";

        function e() {
            x(!0)
        }
        var n = {};
        t.respond = n, n.update = function() {};
        var i = [],
            o = function() {
                var e = !1;
                try {
                    e = new t.XMLHttpRequest
                } catch (n) {
                    e = new t.ActiveXObject("Microsoft.XMLHTTP")
                }
                return function() {
                    return e
                }
            }(),
            r = function(t, e) {
                var n = o();
                n && (n.open("GET", t, !0), n.onreadystatechange = function() {
                    4 !== n.readyState || 200 !== n.status && 304 !== n.status || e(n.responseText)
                }, 4 !== n.readyState && n.send(null))
            },
            s = function(t) {
                return t.replace(n.regex.minmaxwh, "").match(n.regex.other)
            };
        if (n.ajax = r, n.queue = i, n.unsupportedmq = s, n.regex = {
                media: /@media[^\{]+\{([^\{\}]*\{[^\}\{]*\})+/gi,
                keyframes: /@(?:\-(?:o|moz|webkit)\-)?keyframes[^\{]+\{(?:[^\{\}]*\{[^\}\{]*\})+[^\}]*\}/gi,
                comments: /\/\*[^*]*\*+([^\/][^*]*\*+)*\//gi,
                urls: /(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g,
                findStyles: /@media *([^\{]+)\{([\S\s]+?)$/,
                only: /(only\s+)?([a-zA-Z]+)\s?/,
                minw: /\(\s*min\-width\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/,
                maxw: /\(\s*max\-width\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/,
                minmaxwh: /\(\s*m(in|ax)\-(height|width)\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/gi,
                other: /\([^\)]*\)/g
            }, n.mediaQueriesSupported = t.matchMedia && null !== t.matchMedia("only all") && t.matchMedia("only all").matches, !n.mediaQueriesSupported) {
            var a, l, c, u = t.document,
                p = u.documentElement,
                d = [],
                f = [],
                h = [],
                g = {},
                m = u.getElementsByTagName("head")[0] || p,
                v = u.getElementsByTagName("base")[0],
                y = m.getElementsByTagName("link"),
                b = function() {
                    var t, e = u.createElement("div"),
                        n = u.body,
                        i = p.style.fontSize,
                        o = n && n.style.fontSize,
                        r = !1;
                    return e.style.cssText = "position:absolute;font-size:1em;width:1em", n || ((n = r = u.createElement("body")).style.background = "none"), p.style.fontSize = "100%", n.style.fontSize = "100%", n.appendChild(e), r && p.insertBefore(n, p.firstChild), t = e.offsetWidth, r ? p.removeChild(n) : n.removeChild(e), p.style.fontSize = i, o && (n.style.fontSize = o), c = parseFloat(t)
                },
                x = function(e) {
                    var n = "clientWidth",
                        i = p[n],
                        o = "CSS1Compat" === u.compatMode && i || u.body[n] || i,
                        r = {},
                        s = y[y.length - 1],
                        g = (new Date).getTime();
                    if (e && a && 30 > g - a) return t.clearTimeout(l), void(l = t.setTimeout(x, 30));
                    for (var v in a = g, d)
                        if (d.hasOwnProperty(v)) {
                            var w = d[v],
                                T = w.minw,
                                C = w.maxw,
                                E = null === T,
                                k = null === C;
                            T && (T = parseFloat(T) * (T.indexOf("em") > -1 ? c || b() : 1)), C && (C = parseFloat(C) * (C.indexOf("em") > -1 ? c || b() : 1)), w.hasquery && (E && k || !(E || o >= T) || !(k || C >= o)) || (r[w.media] || (r[w.media] = []), r[w.media].push(f[w.rules]))
                        } for (var S in h) h.hasOwnProperty(S) && h[S] && h[S].parentNode === m && m.removeChild(h[S]);
                    for (var $ in h.length = 0, r)
                        if (r.hasOwnProperty($)) {
                            var N = u.createElement("style"),
                                D = r[$].join("\n");
                            N.type = "text/css", N.media = $, m.insertBefore(N, s.nextSibling), N.styleSheet ? N.styleSheet.cssText = D : N.appendChild(u.createTextNode(D)), h.push(N)
                        }
                },
                w = function(t, e, i) {
                    var o = t.replace(n.regex.comments, "").replace(n.regex.keyframes, "").match(n.regex.media),
                        r = o && o.length || 0,
                        a = function(t) {
                            return t.replace(n.regex.urls, "$1" + e + "$2$3")
                        },
                        l = !r && i;
                    (e = e.substring(0, e.lastIndexOf("/"))).length && (e += "/"), l && (r = 1);
                    for (var c = 0; r > c; c++) {
                        var u, p, h, g;
                        l ? (u = i, f.push(a(t))) : (u = o[c].match(n.regex.findStyles) && RegExp.$1, f.push(RegExp.$2 && a(RegExp.$2))), g = (h = u.split(",")).length;
                        for (var m = 0; g > m; m++) p = h[m], s(p) || d.push({
                            media: p.split("(")[0].match(n.regex.only) && RegExp.$2 || "all",
                            rules: f.length - 1,
                            hasquery: p.indexOf("(") > -1,
                            minw: p.match(n.regex.minw) && parseFloat(RegExp.$1) + (RegExp.$2 || ""),
                            maxw: p.match(n.regex.maxw) && parseFloat(RegExp.$1) + (RegExp.$2 || "")
                        })
                    }
                    x()
                },
                T = function() {
                    if (i.length) {
                        var e = i.shift();
                        r(e.href, function(n) {
                            w(n, e.href, e.media), g[e.href] = !0, t.setTimeout(function() {
                                T()
                            }, 0)
                        })
                    }
                },
                C = function() {
                    for (var e = 0; e < y.length; e++) {
                        var n = y[e],
                            o = n.href,
                            r = n.media,
                            s = n.rel && "stylesheet" === n.rel.toLowerCase();
                        o && s && !g[o] && (n.styleSheet && n.styleSheet.rawCssText ? (w(n.styleSheet.rawCssText, o, r), g[o] = !0) : (!/^([a-zA-Z:]*\/\/)/.test(o) && !v || o.replace(RegExp.$1, "").split("/")[0] === t.location.host) && ("//" === o.substring(0, 2) && (o = t.location.protocol + o), i.push({
                            href: o,
                            media: r
                        })))
                    }
                    T()
                };
            C(), n.update = C, n.getEmValue = b, t.addEventListener ? t.addEventListener("resize", e, !1) : t.attachEvent && t.attachEvent("onresize", e)
        }
    }(this), "undefined" == typeof jQuery) throw new Error("Bootstrap's JavaScript requires jQuery");
! function(t) {
    "use strict";
    var e = jQuery.fn.jquery.split(" ")[0].split(".");
    if (e[0] < 2 && e[1] < 9 || 1 == e[0] && 9 == e[1] && e[2] < 1) throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher")
}(),
function(t) {
    "use strict";
    t.fn.emulateTransitionEnd = function(e) {
        var n = !1,
            i = this;
        t(this).one("bsTransitionEnd", function() {
            n = !0
        });
        return setTimeout(function() {
            n || t(i).trigger(t.support.transition.end)
        }, e), this
    }, t(function() {
        t.support.transition = function() {
            var t = document.createElement("bootstrap"),
                e = {
                    WebkitTransition: "webkitTransitionEnd",
                    MozTransition: "transitionend",
                    OTransition: "oTransitionEnd otransitionend",
                    transition: "transitionend"
                };
            for (var n in e)
                if (void 0 !== t.style[n]) return {
                    end: e[n]
                };
            return !1
        }(), t.support.transition && (t.event.special.bsTransitionEnd = {
            bindType: t.support.transition.end,
            delegateType: t.support.transition.end,
            handle: function(e) {
                return t(e.target).is(this) ? e.handleObj.handler.apply(this, arguments) : void 0
            }
        })
    })
}(jQuery),
function(t) {
    "use strict";
    var e = '[data-dismiss="alert"]',
        n = function(n) {
            t(n).on("click", e, this.close)
        };
    n.VERSION = "3.3.4", n.TRANSITION_DURATION = 150, n.prototype.close = function(e) {
        function i() {
            s.detach().trigger("closed.bs.alert").remove()
        }
        var o = t(this),
            r = o.attr("data-target");
        r || (r = (r = o.attr("href")) && r.replace(/.*(?=#[^\s]*$)/, ""));
        var s = t(r);
        e && e.preventDefault(), s.length || (s = o.closest(".alert")), s.trigger(e = t.Event("close.bs.alert")), e.isDefaultPrevented() || (s.removeClass("in"), t.support.transition && s.hasClass("fade") ? s.one("bsTransitionEnd", i).emulateTransitionEnd(n.TRANSITION_DURATION) : i())
    };
    var i = t.fn.alert;
    t.fn.alert = function(e) {
        return this.each(function() {
            var i = t(this),
                o = i.data("bs.alert");
            o || i.data("bs.alert", o = new n(this)), "string" == typeof e && o[e].call(i)
        })
    }, t.fn.alert.Constructor = n, t.fn.alert.noConflict = function() {
        return t.fn.alert = i, this
    }, t(document).on("click.bs.alert.data-api", e, n.prototype.close)
}(jQuery),
function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var i = t(this),
                o = i.data("bs.button"),
                r = "object" == typeof e && e;
            o || i.data("bs.button", o = new n(this, r)), "toggle" == e ? o.toggle() : e && o.setState(e)
        })
    }
    var n = function(e, i) {
        this.$element = t(e), this.options = t.extend({}, n.DEFAULTS, i), this.isLoading = !1
    };
    n.VERSION = "3.3.4", n.DEFAULTS = {
        loadingText: "loading..."
    }, n.prototype.setState = function(e) {
        var n = "disabled",
            i = this.$element,
            o = i.is("input") ? "val" : "html",
            r = i.data();
        e += "Text", null == r.resetText && i.data("resetText", i[o]()), setTimeout(t.proxy(function() {
            i[o](null == r[e] ? this.options[e] : r[e]), "loadingText" == e ? (this.isLoading = !0, i.addClass(n).attr(n, n)) : this.isLoading && (this.isLoading = !1, i.removeClass(n).removeAttr(n))
        }, this), 0)
    }, n.prototype.toggle = function() {
        var t = !0,
            e = this.$element.closest('[data-toggle="buttons"]');
        if (e.length) {
            var n = this.$element.find("input");
            "radio" == n.prop("type") && (n.prop("checked") && this.$element.hasClass("active") ? t = !1 : e.find(".active").removeClass("active")), t && n.prop("checked", !this.$element.hasClass("active")).trigger("change")
        } else this.$element.attr("aria-pressed", !this.$element.hasClass("active"));
        t && this.$element.toggleClass("active")
    };
    var i = t.fn.button;
    t.fn.button = e, t.fn.button.Constructor = n, t.fn.button.noConflict = function() {
        return t.fn.button = i, this
    }, t(document).on("click.bs.button.data-api", '[data-toggle^="button"]', function(n) {
        var i = t(n.target);
        i.hasClass("btn") || (i = i.closest(".btn")), e.call(i, "toggle"), n.preventDefault()
    }).on("focus.bs.button.data-api blur.bs.button.data-api", '[data-toggle^="button"]', function(e) {
        t(e.target).closest(".btn").toggleClass("focus", /^focus(in)?$/.test(e.type))
    })
}(jQuery),
function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var i = t(this),
                o = i.data("bs.carousel"),
                r = t.extend({}, n.DEFAULTS, i.data(), "object" == typeof e && e),
                s = "string" == typeof e ? e : r.slide;
            o || i.data("bs.carousel", o = new n(this, r)), "number" == typeof e ? o.to(e) : s ? o[s]() : r.interval && o.pause().cycle()
        })
    }
    var n = function(e, n) {
        this.$element = t(e), this.$indicators = this.$element.find(".carousel-indicators"), this.options = n, this.paused = null, this.sliding = null, this.interval = null, this.$active = null, this.$items = null, this.options.keyboard && this.$element.on("keydown.bs.carousel", t.proxy(this.keydown, this)), "hover" == this.options.pause && !("ontouchstart" in document.documentElement) && this.$element.on("mouseenter.bs.carousel", t.proxy(this.pause, this)).on("mouseleave.bs.carousel", t.proxy(this.cycle, this))
    };
    n.VERSION = "3.3.4", n.TRANSITION_DURATION = 600, n.DEFAULTS = {
        interval: 5e3,
        pause: "hover",
        wrap: !0,
        keyboard: !0
    }, n.prototype.keydown = function(t) {
        if (!/input|textarea/i.test(t.target.tagName)) {
            switch (t.which) {
                case 37:
                    this.prev();
                    break;
                case 39:
                    this.next();
                    break;
                default:
                    return
            }
            t.preventDefault()
        }
    }, n.prototype.cycle = function(e) {
        return e || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(t.proxy(this.next, this), this.options.interval)), this
    }, n.prototype.getItemIndex = function(t) {
        return this.$items = t.parent().children(".item"), this.$items.index(t || this.$active)
    }, n.prototype.getItemForDirection = function(t, e) {
        var n = this.getItemIndex(e);
        if (("prev" == t && 0 === n || "next" == t && n == this.$items.length - 1) && !this.options.wrap) return e;
        var i = (n + ("prev" == t ? -1 : 1)) % this.$items.length;
        return this.$items.eq(i)
    }, n.prototype.to = function(t) {
        var e = this,
            n = this.getItemIndex(this.$active = this.$element.find(".item.active"));
        return t > this.$items.length - 1 || 0 > t ? void 0 : this.sliding ? this.$element.one("slid.bs.carousel", function() {
            e.to(t)
        }) : n == t ? this.pause().cycle() : this.slide(t > n ? "next" : "prev", this.$items.eq(t))
    }, n.prototype.pause = function(e) {
        return e || (this.paused = !0), this.$element.find(".next, .prev").length && t.support.transition && (this.$element.trigger(t.support.transition.end), this.cycle(!0)), this.interval = clearInterval(this.interval), this
    }, n.prototype.next = function() {
        return this.sliding ? void 0 : this.slide("next")
    }, n.prototype.prev = function() {
        return this.sliding ? void 0 : this.slide("prev")
    }, n.prototype.slide = function(e, i) {
        var o = this.$element.find(".item.active"),
            r = i || this.getItemForDirection(e, o),
            s = this.interval,
            a = "next" == e ? "left" : "right",
            l = this;
        if (r.hasClass("active")) return this.sliding = !1;
        var c = r[0],
            u = t.Event("slide.bs.carousel", {
                relatedTarget: c,
                direction: a
            });
        if (this.$element.trigger(u), !u.isDefaultPrevented()) {
            if (this.sliding = !0, s && this.pause(), this.$indicators.length) {
                this.$indicators.find(".active").removeClass("active");
                var p = t(this.$indicators.children()[this.getItemIndex(r)]);
                p && p.addClass("active")
            }
            var d = t.Event("slid.bs.carousel", {
                relatedTarget: c,
                direction: a
            });
            return t.support.transition && this.$element.hasClass("slide") ? (r.addClass(e), r[0].offsetWidth, o.addClass(a), r.addClass(a), o.one("bsTransitionEnd", function() {
                r.removeClass([e, a].join(" ")).addClass("active"), o.removeClass(["active", a].join(" ")), l.sliding = !1, setTimeout(function() {
                    l.$element.trigger(d)
                }, 0)
            }).emulateTransitionEnd(n.TRANSITION_DURATION)) : (o.removeClass("active"), r.addClass("active"), this.sliding = !1, this.$element.trigger(d)), s && this.cycle(), this
        }
    };
    var i = t.fn.carousel;
    t.fn.carousel = e, t.fn.carousel.Constructor = n, t.fn.carousel.noConflict = function() {
        return t.fn.carousel = i, this
    };
    var o = function(n) {
        var i, o = t(this),
            r = t(o.attr("data-target") || (i = o.attr("href")) && i.replace(/.*(?=#[^\s]+$)/, ""));
        if (r.hasClass("carousel")) {
            var s = t.extend({}, r.data(), o.data()),
                a = o.attr("data-slide-to");
            a && (s.interval = !1), e.call(r, s), a && r.data("bs.carousel").to(a), n.preventDefault()
        }
    };
    t(document).on("click.bs.carousel.data-api", "[data-slide]", o).on("click.bs.carousel.data-api", "[data-slide-to]", o), t(window).on("load", function() {
        t('[data-ride="carousel"]').each(function() {
            var n = t(this);
            e.call(n, n.data())
        })
    })
}(jQuery),
function(t) {
    "use strict";

    function e(e) {
        var n, i = e.attr("data-target") || (n = e.attr("href")) && n.replace(/.*(?=#[^\s]+$)/, "");
        return t(i)
    }

    function n(e) {
        return this.each(function() {
            var n = t(this),
                o = n.data("bs.collapse"),
                r = t.extend({}, i.DEFAULTS, n.data(), "object" == typeof e && e);
            !o && r.toggle && /show|hide/.test(e) && (r.toggle = !1), o || n.data("bs.collapse", o = new i(this, r)), "string" == typeof e && o[e]()
        })
    }
    var i = function(e, n) {
        this.$element = t(e), this.options = t.extend({}, i.DEFAULTS, n), this.$trigger = t('[data-toggle="collapse"][href="#' + e.id + '"],[data-toggle="collapse"][data-target="#' + e.id + '"]'), this.transitioning = null, this.options.parent ? this.$parent = this.getParent() : this.addAriaAndCollapsedClass(this.$element, this.$trigger), this.options.toggle && this.toggle()
    };
    i.VERSION = "3.3.4", i.TRANSITION_DURATION = 350, i.DEFAULTS = {
        toggle: !0
    }, i.prototype.dimension = function() {
        return this.$element.hasClass("width") ? "width" : "height"
    }, i.prototype.show = function() {
        if (!this.transitioning && !this.$element.hasClass("in")) {
            var e, o = this.$parent && this.$parent.children(".panel").children(".in, .collapsing");
            if (!(o && o.length && (e = o.data("bs.collapse")) && e.transitioning)) {
                var r = t.Event("show.bs.collapse");
                if (this.$element.trigger(r), !r.isDefaultPrevented()) {
                    o && o.length && (n.call(o, "hide"), e || o.data("bs.collapse", null));
                    var s = this.dimension();
                    this.$element.removeClass("collapse").addClass("collapsing")[s](0).attr("aria-expanded", !0), this.$trigger.removeClass("collapsed").attr("aria-expanded", !0), this.transitioning = 1;
                    var a = function() {
                        this.$element.removeClass("collapsing").addClass("collapse in")[s](""), this.transitioning = 0, this.$element.trigger("shown.bs.collapse")
                    };
                    if (!t.support.transition) return a.call(this);
                    var l = t.camelCase(["scroll", s].join("-"));
                    this.$element.one("bsTransitionEnd", t.proxy(a, this)).emulateTransitionEnd(i.TRANSITION_DURATION)[s](this.$element[0][l])
                }
            }
        }
    }, i.prototype.hide = function() {
        if (!this.transitioning && this.$element.hasClass("in")) {
            var e = t.Event("hide.bs.collapse");
            if (this.$element.trigger(e), !e.isDefaultPrevented()) {
                var n = this.dimension();
                this.$element[n](this.$element[n]())[0].offsetHeight, this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded", !1), this.$trigger.addClass("collapsed").attr("aria-expanded", !1), this.transitioning = 1;
                var o = function() {
                    this.transitioning = 0, this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")
                };
                return t.support.transition ? void this.$element[n](0).one("bsTransitionEnd", t.proxy(o, this)).emulateTransitionEnd(i.TRANSITION_DURATION) : o.call(this)
            }
        }
    }, i.prototype.toggle = function() {
        this[this.$element.hasClass("in") ? "hide" : "show"]()
    }, i.prototype.getParent = function() {
        return t(this.options.parent).find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]').each(t.proxy(function(n, i) {
            var o = t(i);
            this.addAriaAndCollapsedClass(e(o), o)
        }, this)).end()
    }, i.prototype.addAriaAndCollapsedClass = function(t, e) {
        var n = t.hasClass("in");
        t.attr("aria-expanded", n), e.toggleClass("collapsed", !n).attr("aria-expanded", n)
    };
    var o = t.fn.collapse;
    t.fn.collapse = n, t.fn.collapse.Constructor = i, t.fn.collapse.noConflict = function() {
        return t.fn.collapse = o, this
    }, t(document).on("click.bs.collapse.data-api", '[data-toggle="collapse"]', function(i) {
        var o = t(this);
        o.attr("data-target") || i.preventDefault();
        var r = e(o),
            s = r.data("bs.collapse") ? "toggle" : o.data();
        n.call(r, s)
    })
}(jQuery),
function(t) {
    "use strict";

    function e(e) {
        e && 3 === e.which || (t(i).remove(), t(o).each(function() {
            var i = t(this),
                o = n(i),
                r = {
                    relatedTarget: this
                };
            o.hasClass("open") && (o.trigger(e = t.Event("hide.bs.dropdown", r)), e.isDefaultPrevented() || (i.attr("aria-expanded", "false"), o.removeClass("open").trigger("hidden.bs.dropdown", r)))
        }))
    }

    function n(e) {
        var n = e.attr("data-target");
        n || (n = (n = e.attr("href")) && /#[A-Za-z]/.test(n) && n.replace(/.*(?=#[^\s]*$)/, ""));
        var i = n && t(n);
        return i && i.length ? i : e.parent()
    }
    var i = ".dropdown-backdrop",
        o = '[data-toggle="dropdown"]',
        r = function(e) {
            t(e).on("click.bs.dropdown", this.toggle)
        };
    r.VERSION = "3.3.4", r.prototype.toggle = function(i) {
        var o = t(this);
        if (!o.is(".disabled, :disabled")) {
            var r = n(o),
                s = r.hasClass("open");
            if (e(), !s) {
                "ontouchstart" in document.documentElement && !r.closest(".navbar-nav").length && t('<div class="dropdown-backdrop"/>').insertAfter(t(this)).on("click", e);
                var a = {
                    relatedTarget: this
                };
                if (r.trigger(i = t.Event("show.bs.dropdown", a)), i.isDefaultPrevented()) return;
                o.trigger("focus").attr("aria-expanded", "true"), r.toggleClass("open").trigger("shown.bs.dropdown", a)
            }
            return !1
        }
    }, r.prototype.keydown = function(e) {
        if (/(38|40|27|32)/.test(e.which) && !/input|textarea/i.test(e.target.tagName)) {
            var i = t(this);
            if (e.preventDefault(), e.stopPropagation(), !i.is(".disabled, :disabled")) {
                var r = n(i),
                    s = r.hasClass("open");
                if (!s && 27 != e.which || s && 27 == e.which) return 27 == e.which && r.find(o).trigger("focus"), i.trigger("click");
                var a = " li:not(.disabled):visible a",
                    l = r.find('[role="menu"]' + a + ', [role="listbox"]' + a);
                if (l.length) {
                    var c = l.index(e.target);
                    38 == e.which && c > 0 && c--, 40 == e.which && c < l.length - 1 && c++, ~c || (c = 0), l.eq(c).trigger("focus")
                }
            }
        }
    };
    var s = t.fn.dropdown;
    t.fn.dropdown = function(e) {
        return this.each(function() {
            var n = t(this),
                i = n.data("bs.dropdown");
            i || n.data("bs.dropdown", i = new r(this)), "string" == typeof e && i[e].call(n)
        })
    }, t.fn.dropdown.Constructor = r, t.fn.dropdown.noConflict = function() {
        return t.fn.dropdown = s, this
    }, t(document).on("click.bs.dropdown.data-api", e).on("click.bs.dropdown.data-api", ".dropdown form", function(t) {
        t.stopPropagation()
    }).on("click.bs.dropdown.data-api", o, r.prototype.toggle).on("keydown.bs.dropdown.data-api", o, r.prototype.keydown).on("keydown.bs.dropdown.data-api", '[role="menu"]', r.prototype.keydown).on("keydown.bs.dropdown.data-api", '[role="listbox"]', r.prototype.keydown)
}(jQuery),
function(t) {
    "use strict";

    function e(e, i) {
        return this.each(function() {
            var o = t(this),
                r = o.data("bs.modal"),
                s = t.extend({}, n.DEFAULTS, o.data(), "object" == typeof e && e);
            r || o.data("bs.modal", r = new n(this, s)), "string" == typeof e ? r[e](i) : s.show && r.show(i)
        })
    }
    var n = function(e, n) {
        this.options = n, this.$body = t(document.body), this.$element = t(e), this.$dialog = this.$element.find(".modal-dialog"), this.$backdrop = null, this.isShown = null, this.originalBodyPad = null, this.scrollbarWidth = 0, this.ignoreBackdropClick = !1, this.options.remote && this.$element.find(".modal-content").load(this.options.remote, t.proxy(function() {
            this.$element.trigger("loaded.bs.modal")
        }, this))
    };
    n.VERSION = "3.3.4", n.TRANSITION_DURATION = 300, n.BACKDROP_TRANSITION_DURATION = 150, n.DEFAULTS = {
        backdrop: !0,
        keyboard: !0,
        show: !0
    }, n.prototype.toggle = function(t) {
        return this.isShown ? this.hide() : this.show(t)
    }, n.prototype.show = function(e) {
        var i = this,
            o = t.Event("show.bs.modal", {
                relatedTarget: e
            });
        this.$element.trigger(o), this.isShown || o.isDefaultPrevented() || (this.isShown = !0, this.checkScrollbar(), this.setScrollbar(), this.$body.addClass("modal-open"), this.escape(), this.resize(), this.$element.on("click.dismiss.bs.modal", '[data-dismiss="modal"]', t.proxy(this.hide, this)), this.$dialog.on("mousedown.dismiss.bs.modal", function() {
            i.$element.one("mouseup.dismiss.bs.modal", function(e) {
                t(e.target).is(i.$element) && (i.ignoreBackdropClick = !0)
            })
        }), this.backdrop(function() {
            var o = t.support.transition && i.$element.hasClass("fade");
            i.$element.parent().length || i.$element.appendTo(i.$body), i.$element.show().scrollTop(0), i.adjustDialog(), o && i.$element[0].offsetWidth, i.$element.addClass("in").attr("aria-hidden", !1), i.enforceFocus();
            var r = t.Event("shown.bs.modal", {
                relatedTarget: e
            });
            o ? i.$dialog.one("bsTransitionEnd", function() {
                i.$element.trigger("focus").trigger(r)
            }).emulateTransitionEnd(n.TRANSITION_DURATION) : i.$element.trigger("focus").trigger(r)
        }))
    }, n.prototype.hide = function(e) {
        e && e.preventDefault(), e = t.Event("hide.bs.modal"), this.$element.trigger(e), this.isShown && !e.isDefaultPrevented() && (this.isShown = !1, this.escape(), this.resize(), t(document).off("focusin.bs.modal"), this.$element.removeClass("in").attr("aria-hidden", !0).off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"), this.$dialog.off("mousedown.dismiss.bs.modal"), t.support.transition && this.$element.hasClass("fade") ? this.$element.one("bsTransitionEnd", t.proxy(this.hideModal, this)).emulateTransitionEnd(n.TRANSITION_DURATION) : this.hideModal())
    }, n.prototype.enforceFocus = function() {
        t(document).off("focusin.bs.modal").on("focusin.bs.modal", t.proxy(function(t) {
            this.$element[0] === t.target || this.$element.has(t.target).length || this.$element.trigger("focus")
        }, this))
    }, n.prototype.escape = function() {
        this.isShown && this.options.keyboard ? this.$element.on("keydown.dismiss.bs.modal", t.proxy(function(t) {
            27 == t.which && this.hide()
        }, this)) : this.isShown || this.$element.off("keydown.dismiss.bs.modal")
    }, n.prototype.resize = function() {
        this.isShown ? t(window).on("resize.bs.modal", t.proxy(this.handleUpdate, this)) : t(window).off("resize.bs.modal")
    }, n.prototype.hideModal = function() {
        var t = this;
        this.$element.hide(), this.backdrop(function() {
            t.$body.removeClass("modal-open"), t.resetAdjustments(), t.resetScrollbar(), t.$element.trigger("hidden.bs.modal")
        })
    }, n.prototype.removeBackdrop = function() {
        this.$backdrop && this.$backdrop.remove(), this.$backdrop = null
    }, n.prototype.backdrop = function(e) {
        var i = this,
            o = this.$element.hasClass("fade") ? "fade" : "";
        if (this.isShown && this.options.backdrop) {
            var r = t.support.transition && o;
            if (this.$backdrop = t('<div class="modal-backdrop ' + o + '" />').appendTo(this.$body), this.$element.on("click.dismiss.bs.modal", t.proxy(function(t) {
                    return this.ignoreBackdropClick ? void(this.ignoreBackdropClick = !1) : void(t.target === t.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus() : this.hide()))
                }, this)), r && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in"), !e) return;
            r ? this.$backdrop.one("bsTransitionEnd", e).emulateTransitionEnd(n.BACKDROP_TRANSITION_DURATION) : e()
        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass("in");
            var s = function() {
                i.removeBackdrop(), e && e()
            };
            t.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one("bsTransitionEnd", s).emulateTransitionEnd(n.BACKDROP_TRANSITION_DURATION) : s()
        } else e && e()
    }, n.prototype.handleUpdate = function() {
        this.adjustDialog()
    }, n.prototype.adjustDialog = function() {
        var t = this.$element[0].scrollHeight > document.documentElement.clientHeight;
        this.$element.css({
            paddingLeft: !this.bodyIsOverflowing && t ? this.scrollbarWidth : "",
            paddingRight: this.bodyIsOverflowing && !t ? this.scrollbarWidth : ""
        })
    }, n.prototype.resetAdjustments = function() {
        this.$element.css({
            paddingLeft: "",
            paddingRight: ""
        })
    }, n.prototype.checkScrollbar = function() {
        var t = window.innerWidth;
        if (!t) {
            var e = document.documentElement.getBoundingClientRect();
            t = e.right - Math.abs(e.left)
        }
        this.bodyIsOverflowing = document.body.clientWidth < t, this.scrollbarWidth = this.measureScrollbar()
    }, n.prototype.setScrollbar = function() {
        var t = parseInt(this.$body.css("padding-right") || 0, 10);
        this.originalBodyPad = document.body.style.paddingRight || "", this.bodyIsOverflowing && this.$body.css("padding-right", t + this.scrollbarWidth)
    }, n.prototype.resetScrollbar = function() {
        this.$body.css("padding-right", this.originalBodyPad)
    }, n.prototype.measureScrollbar = function() {
        var t = document.createElement("div");
        t.className = "modal-scrollbar-measure", this.$body.append(t);
        var e = t.offsetWidth - t.clientWidth;
        return this.$body[0].removeChild(t), e
    };
    var i = t.fn.modal;
    t.fn.modal = e, t.fn.modal.Constructor = n, t.fn.modal.noConflict = function() {
        return t.fn.modal = i, this
    }, t(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function(n) {
        var i = t(this),
            o = i.attr("href"),
            r = t(i.attr("data-target") || o && o.replace(/.*(?=#[^\s]+$)/, "")),
            s = r.data("bs.modal") ? "toggle" : t.extend({
                remote: !/#/.test(o) && o
            }, r.data(), i.data());
        i.is("a") && n.preventDefault(), r.one("show.bs.modal", function(t) {
            t.isDefaultPrevented() || r.one("hidden.bs.modal", function() {
                i.is(":visible") && i.trigger("focus")
            })
        }), e.call(r, s, this)
    })
}(jQuery),
function(t) {
    "use strict";
    var e = function(t, e) {
        this.type = null, this.options = null, this.enabled = null, this.timeout = null, this.hoverState = null, this.$element = null, this.init("tooltip", t, e)
    };
    e.VERSION = "3.3.4", e.TRANSITION_DURATION = 150, e.DEFAULTS = {
        animation: !0,
        placement: "top",
        selector: !1,
        template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover focus",
        title: "",
        delay: 0,
        html: !1,
        container: !1,
        viewport: {
            selector: "body",
            padding: 0
        }
    }, e.prototype.init = function(e, n, i) {
        if (this.enabled = !0, this.type = e, this.$element = t(n), this.options = this.getOptions(i), this.$viewport = this.options.viewport && t(this.options.viewport.selector || this.options.viewport), this.$element[0] instanceof document.constructor && !this.options.selector) throw new Error("`selector` option must be specified when initializing " + this.type + " on the window.document object!");
        for (var o = this.options.trigger.split(" "), r = o.length; r--;) {
            var s = o[r];
            if ("click" == s) this.$element.on("click." + this.type, this.options.selector, t.proxy(this.toggle, this));
            else if ("manual" != s) {
                var a = "hover" == s ? "mouseenter" : "focusin",
                    l = "hover" == s ? "mouseleave" : "focusout";
                this.$element.on(a + "." + this.type, this.options.selector, t.proxy(this.enter, this)), this.$element.on(l + "." + this.type, this.options.selector, t.proxy(this.leave, this))
            }
        }
        this.options.selector ? this._options = t.extend({}, this.options, {
            trigger: "manual",
            selector: ""
        }) : this.fixTitle()
    }, e.prototype.getDefaults = function() {
        return e.DEFAULTS
    }, e.prototype.getOptions = function(e) {
        return (e = t.extend({}, this.getDefaults(), this.$element.data(), e)).delay && "number" == typeof e.delay && (e.delay = {
            show: e.delay,
            hide: e.delay
        }), e
    }, e.prototype.getDelegateOptions = function() {
        var e = {},
            n = this.getDefaults();
        return this._options && t.each(this._options, function(t, i) {
            n[t] != i && (e[t] = i)
        }), e
    }, e.prototype.enter = function(e) {
        var n = e instanceof this.constructor ? e : t(e.currentTarget).data("bs." + this.type);
        return n && n.$tip && n.$tip.is(":visible") ? void(n.hoverState = "in") : (n || (n = new this.constructor(e.currentTarget, this.getDelegateOptions()), t(e.currentTarget).data("bs." + this.type, n)), clearTimeout(n.timeout), n.hoverState = "in", n.options.delay && n.options.delay.show ? void(n.timeout = setTimeout(function() {
            "in" == n.hoverState && n.show()
        }, n.options.delay.show)) : n.show())
    }, e.prototype.leave = function(e) {
        var n = e instanceof this.constructor ? e : t(e.currentTarget).data("bs." + this.type);
        return n || (n = new this.constructor(e.currentTarget, this.getDelegateOptions()), t(e.currentTarget).data("bs." + this.type, n)), clearTimeout(n.timeout), n.hoverState = "out", n.options.delay && n.options.delay.hide ? void(n.timeout = setTimeout(function() {
            "out" == n.hoverState && n.hide()
        }, n.options.delay.hide)) : n.hide()
    }, e.prototype.show = function() {
        var n = t.Event("show.bs." + this.type);
        if (this.hasContent() && this.enabled) {
            this.$element.trigger(n);
            var i = t.contains(this.$element[0].ownerDocument.documentElement, this.$element[0]);
            if (n.isDefaultPrevented() || !i) return;
            var o = this,
                r = this.tip(),
                s = this.getUID(this.type);
            this.setContent(), r.attr("id", s), this.$element.attr("aria-describedby", s), this.options.animation && r.addClass("fade");
            var a = "function" == typeof this.options.placement ? this.options.placement.call(this, r[0], this.$element[0]) : this.options.placement,
                l = /\s?auto?\s?/i,
                c = l.test(a);
            c && (a = a.replace(l, "") || "top"), r.detach().css({
                top: 0,
                left: 0,
                display: "block"
            }).addClass(a).data("bs." + this.type, this), this.options.container ? r.appendTo(this.options.container) : r.insertAfter(this.$element);
            var u = this.getPosition(),
                p = r[0].offsetWidth,
                d = r[0].offsetHeight;
            if (c) {
                var f = a,
                    h = this.options.container ? t(this.options.container) : this.$element.parent(),
                    g = this.getPosition(h);
                a = "bottom" == a && u.bottom + d > g.bottom ? "top" : "top" == a && u.top - d < g.top ? "bottom" : "right" == a && u.right + p > g.width ? "left" : "left" == a && u.left - p < g.left ? "right" : a, r.removeClass(f).addClass(a)
            }
            var m = this.getCalculatedOffset(a, u, p, d);
            this.applyPlacement(m, a);
            var v = function() {
                var t = o.hoverState;
                o.$element.trigger("shown.bs." + o.type), o.hoverState = null, "out" == t && o.leave(o)
            };
            t.support.transition && this.$tip.hasClass("fade") ? r.one("bsTransitionEnd", v).emulateTransitionEnd(e.TRANSITION_DURATION) : v()
        }
    }, e.prototype.applyPlacement = function(e, n) {
        var i = this.tip(),
            o = i[0].offsetWidth,
            r = i[0].offsetHeight,
            s = parseInt(i.css("margin-top"), 10),
            a = parseInt(i.css("margin-left"), 10);
        isNaN(s) && (s = 0), isNaN(a) && (a = 0), e.top = e.top + s, e.left = e.left + a, t.offset.setOffset(i[0], t.extend({
            using: function(t) {
                i.css({
                    top: Math.round(t.top),
                    left: Math.round(t.left)
                })
            }
        }, e), 0), i.addClass("in");
        var l = i[0].offsetWidth,
            c = i[0].offsetHeight;
        "top" == n && c != r && (e.top = e.top + r - c);
        var u = this.getViewportAdjustedDelta(n, e, l, c);
        u.left ? e.left += u.left : e.top += u.top;
        var p = /top|bottom/.test(n),
            d = p ? 2 * u.left - o + l : 2 * u.top - r + c,
            f = p ? "offsetWidth" : "offsetHeight";
        i.offset(e), this.replaceArrow(d, i[0][f], p)
    }, e.prototype.replaceArrow = function(t, e, n) {
        this.arrow().css(n ? "left" : "top", 50 * (1 - t / e) + "%").css(n ? "top" : "left", "")
    }, e.prototype.setContent = function() {
        var t = this.tip(),
            e = this.getTitle();
        t.find(".tooltip-inner")[this.options.html ? "html" : "text"](e), t.removeClass("fade in top bottom left right")
    }, e.prototype.hide = function(n) {
        function i() {
            "in" != o.hoverState && r.detach(), o.$element.removeAttr("aria-describedby").trigger("hidden.bs." + o.type), n && n()
        }
        var o = this,
            r = t(this.$tip),
            s = t.Event("hide.bs." + this.type);
        return this.$element.trigger(s), s.isDefaultPrevented() ? void 0 : (r.removeClass("in"), t.support.transition && r.hasClass("fade") ? r.one("bsTransitionEnd", i).emulateTransitionEnd(e.TRANSITION_DURATION) : i(), this.hoverState = null, this)
    }, e.prototype.fixTitle = function() {
        var t = this.$element;
        (t.attr("title") || "string" != typeof t.attr("data-original-title")) && t.attr("data-original-title", t.attr("title") || "").attr("title", "")
    }, e.prototype.hasContent = function() {
        return this.getTitle()
    }, e.prototype.getPosition = function(e) {
        var n = (e = e || this.$element)[0],
            i = "BODY" == n.tagName,
            o = n.getBoundingClientRect();
        null == o.width && (o = t.extend({}, o, {
            width: o.right - o.left,
            height: o.bottom - o.top
        }));
        var r = i ? {
                top: 0,
                left: 0
            } : e.offset(),
            s = {
                scroll: i ? document.documentElement.scrollTop || document.body.scrollTop : e.scrollTop()
            },
            a = i ? {
                width: t(window).width(),
                height: t(window).height()
            } : null;
        return t.extend({}, o, s, a, r)
    }, e.prototype.getCalculatedOffset = function(t, e, n, i) {
        return "bottom" == t ? {
            top: e.top + e.height,
            left: e.left + e.width / 2 - n / 2
        } : "top" == t ? {
            top: e.top - i,
            left: e.left + e.width / 2 - n / 2
        } : "left" == t ? {
            top: e.top + e.height / 2 - i / 2,
            left: e.left - n
        } : {
            top: e.top + e.height / 2 - i / 2,
            left: e.left + e.width
        }
    }, e.prototype.getViewportAdjustedDelta = function(t, e, n, i) {
        var o = {
            top: 0,
            left: 0
        };
        if (!this.$viewport) return o;
        var r = this.options.viewport && this.options.viewport.padding || 0,
            s = this.getPosition(this.$viewport);
        if (/right|left/.test(t)) {
            var a = e.top - r - s.scroll,
                l = e.top + r - s.scroll + i;
            a < s.top ? o.top = s.top - a : l > s.top + s.height && (o.top = s.top + s.height - l)
        } else {
            var c = e.left - r,
                u = e.left + r + n;
            c < s.left ? o.left = s.left - c : u > s.width && (o.left = s.left + s.width - u)
        }
        return o
    }, e.prototype.getTitle = function() {
        var t = this.$element,
            e = this.options;
        return t.attr("data-original-title") || ("function" == typeof e.title ? e.title.call(t[0]) : e.title)
    }, e.prototype.getUID = function(t) {
        do {
            t += ~~(1e6 * Math.random())
        } while (document.getElementById(t));
        return t
    }, e.prototype.tip = function() {
        return this.$tip = this.$tip || t(this.options.template)
    }, e.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
    }, e.prototype.enable = function() {
        this.enabled = !0
    }, e.prototype.disable = function() {
        this.enabled = !1
    }, e.prototype.toggleEnabled = function() {
        this.enabled = !this.enabled
    }, e.prototype.toggle = function(e) {
        var n = this;
        e && ((n = t(e.currentTarget).data("bs." + this.type)) || (n = new this.constructor(e.currentTarget, this.getDelegateOptions()), t(e.currentTarget).data("bs." + this.type, n))), n.tip().hasClass("in") ? n.leave(n) : n.enter(n)
    }, e.prototype.destroy = function() {
        var t = this;
        clearTimeout(this.timeout), this.hide(function() {
            t.$element.off("." + t.type).removeData("bs." + t.type)
        })
    };
    var n = t.fn.tooltip;
    t.fn.tooltip = function(n) {
        return this.each(function() {
            var i = t(this),
                o = i.data("bs.tooltip"),
                r = "object" == typeof n && n;
            (o || !/destroy|hide/.test(n)) && (o || i.data("bs.tooltip", o = new e(this, r)), "string" == typeof n && o[n]())
        })
    }, t.fn.tooltip.Constructor = e, t.fn.tooltip.noConflict = function() {
        return t.fn.tooltip = n, this
    }
}(jQuery),
function(t) {
    "use strict";
    var e = function(t, e) {
        this.init("popover", t, e)
    };
    if (!t.fn.tooltip) throw new Error("Popover requires tooltip.js");
    e.VERSION = "3.3.4", e.DEFAULTS = t.extend({}, t.fn.tooltip.Constructor.DEFAULTS, {
        placement: "right",
        trigger: "click",
        content: "",
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    }), e.prototype = t.extend({}, t.fn.tooltip.Constructor.prototype), e.prototype.constructor = e, e.prototype.getDefaults = function() {
        return e.DEFAULTS
    }, e.prototype.setContent = function() {
        var t = this.tip(),
            e = this.getTitle(),
            n = this.getContent();
        t.find(".popover-title")[this.options.html ? "html" : "text"](e), t.find(".popover-content").children().detach().end()[this.options.html ? "string" == typeof n ? "html" : "append" : "text"](n), t.removeClass("fade top bottom left right in"), t.find(".popover-title").html() || t.find(".popover-title").hide()
    }, e.prototype.hasContent = function() {
        return this.getTitle() || this.getContent()
    }, e.prototype.getContent = function() {
        var t = this.$element,
            e = this.options;
        return t.attr("data-content") || ("function" == typeof e.content ? e.content.call(t[0]) : e.content)
    }, e.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".arrow")
    };
    var n = t.fn.popover;
    t.fn.popover = function(n) {
        return this.each(function() {
            var i = t(this),
                o = i.data("bs.popover"),
                r = "object" == typeof n && n;
            (o || !/destroy|hide/.test(n)) && (o || i.data("bs.popover", o = new e(this, r)), "string" == typeof n && o[n]())
        })
    }, t.fn.popover.Constructor = e, t.fn.popover.noConflict = function() {
        return t.fn.popover = n, this
    }
}(jQuery),
function(t) {
    "use strict";

    function e(n, i) {
        this.$body = t(document.body), this.$scrollElement = t(t(n).is(document.body) ? window : n), this.options = t.extend({}, e.DEFAULTS, i), this.selector = (this.options.target || "") + " .nav li > a", this.offsets = [], this.targets = [], this.activeTarget = null, this.scrollHeight = 0, this.$scrollElement.on("scroll.bs.scrollspy", t.proxy(this.process, this)), this.refresh(), this.process()
    }

    function n(n) {
        return this.each(function() {
            var i = t(this),
                o = i.data("bs.scrollspy"),
                r = "object" == typeof n && n;
            o || i.data("bs.scrollspy", o = new e(this, r)), "string" == typeof n && o[n]()
        })
    }
    e.VERSION = "3.3.4", e.DEFAULTS = {
        offset: 10
    }, e.prototype.getScrollHeight = function() {
        return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
    }, e.prototype.refresh = function() {
        var e = this,
            n = "offset",
            i = 0;
        this.offsets = [], this.targets = [], this.scrollHeight = this.getScrollHeight(), t.isWindow(this.$scrollElement[0]) || (n = "position", i = this.$scrollElement.scrollTop()), this.$body.find(this.selector).map(function() {
            var e = t(this),
                o = e.data("target") || e.attr("href"),
                r = /^#./.test(o) && t(o);
            return r && r.length && r.is(":visible") && [
                [r[n]().top + i, o]
            ] || null
        }).sort(function(t, e) {
            return t[0] - e[0]
        }).each(function() {
            e.offsets.push(this[0]), e.targets.push(this[1])
        })
    }, e.prototype.process = function() {
        var t, e = this.$scrollElement.scrollTop() + this.options.offset,
            n = this.getScrollHeight(),
            i = this.options.offset + n - this.$scrollElement.height(),
            o = this.offsets,
            r = this.targets,
            s = this.activeTarget;
        if (this.scrollHeight != n && this.refresh(), e >= i) return s != (t = r[r.length - 1]) && this.activate(t);
        if (s && e < o[0]) return this.activeTarget = null, this.clear();
        for (t = o.length; t--;) s != r[t] && e >= o[t] && (void 0 === o[t + 1] || e < o[t + 1]) && this.activate(r[t])
    }, e.prototype.activate = function(e) {
        this.activeTarget = e, this.clear();
        var n = this.selector + '[data-target="' + e + '"],' + this.selector + '[href="' + e + '"]',
            i = t(n).parents("li").addClass("active");
        i.parent(".dropdown-menu").length && (i = i.closest("li.dropdown").addClass("active")), i.trigger("activate.bs.scrollspy")
    }, e.prototype.clear = function() {
        t(this.selector).parentsUntil(this.options.target, ".active").removeClass("active")
    };
    var i = t.fn.scrollspy;
    t.fn.scrollspy = n, t.fn.scrollspy.Constructor = e, t.fn.scrollspy.noConflict = function() {
        return t.fn.scrollspy = i, this
    }, t(window).on("load.bs.scrollspy.data-api", function() {
        t('[data-spy="scroll"]').each(function() {
            var e = t(this);
            n.call(e, e.data())
        })
    })
}(jQuery),
function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var i = t(this),
                o = i.data("bs.tab");
            o || i.data("bs.tab", o = new n(this)), "string" == typeof e && o[e]()
        })
    }
    var n = function(e) {
        this.element = t(e)
    };
    n.VERSION = "3.3.4", n.TRANSITION_DURATION = 150, n.prototype.show = function() {
        var e = this.element,
            n = e.closest("ul:not(.dropdown-menu)"),
            i = e.data("target");
        if (i || (i = (i = e.attr("href")) && i.replace(/.*(?=#[^\s]*$)/, "")), !e.parent("li").hasClass("active")) {
            var o = n.find(".active:last a"),
                r = t.Event("hide.bs.tab", {
                    relatedTarget: e[0]
                }),
                s = t.Event("show.bs.tab", {
                    relatedTarget: o[0]
                });
            if (o.trigger(r), e.trigger(s), !s.isDefaultPrevented() && !r.isDefaultPrevented()) {
                var a = t(i);
                this.activate(e.closest("li"), n), this.activate(a, a.parent(), function() {
                    o.trigger({
                        type: "hidden.bs.tab",
                        relatedTarget: e[0]
                    }), e.trigger({
                        type: "shown.bs.tab",
                        relatedTarget: o[0]
                    })
                })
            }
        }
    }, n.prototype.activate = function(e, i, o) {
        function r() {
            s.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !1), e.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded", !0), a ? (e[0].offsetWidth, e.addClass("in")) : e.removeClass("fade"), e.parent(".dropdown-menu").length && e.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !0), o && o()
        }
        var s = i.find("> .active"),
            a = o && t.support.transition && (s.length && s.hasClass("fade") || !!i.find("> .fade").length);
        s.length && a ? s.one("bsTransitionEnd", r).emulateTransitionEnd(n.TRANSITION_DURATION) : r(), s.removeClass("in")
    };
    var i = t.fn.tab;
    t.fn.tab = e, t.fn.tab.Constructor = n, t.fn.tab.noConflict = function() {
        return t.fn.tab = i, this
    };
    var o = function(n) {
        n.preventDefault(), e.call(t(this), "show")
    };
    t(document).on("click.bs.tab.data-api", '[data-toggle="tab"]', o).on("click.bs.tab.data-api", '[data-toggle="pill"]', o)
}(jQuery),
function(t) {
    "use strict";

    function e(e) {
        return this.each(function() {
            var i = t(this),
                o = i.data("bs.affix"),
                r = "object" == typeof e && e;
            o || i.data("bs.affix", o = new n(this, r)), "string" == typeof e && o[e]()
        })
    }
    var n = function(e, i) {
        this.options = t.extend({}, n.DEFAULTS, i), this.$target = t(this.options.target).on("scroll.bs.affix.data-api", t.proxy(this.checkPosition, this)).on("click.bs.affix.data-api", t.proxy(this.checkPositionWithEventLoop, this)), this.$element = t(e), this.affixed = null, this.unpin = null, this.pinnedOffset = null, this.checkPosition()
    };
    n.VERSION = "3.3.4", n.RESET = "affix affix-top affix-bottom", n.DEFAULTS = {
        offset: 0,
        target: window
    }, n.prototype.getState = function(t, e, n, i) {
        var o = this.$target.scrollTop(),
            r = this.$element.offset(),
            s = this.$target.height();
        if (null != n && "top" == this.affixed) return n > o && "top";
        if ("bottom" == this.affixed) return null != n ? !(o + this.unpin <= r.top) && "bottom" : !(t - i >= o + s) && "bottom";
        var a = null == this.affixed,
            l = a ? o : r.top;
        return null != n && n >= o ? "top" : null != i && l + (a ? s : e) >= t - i && "bottom"
    }, n.prototype.getPinnedOffset = function() {
        if (this.pinnedOffset) return this.pinnedOffset;
        this.$element.removeClass(n.RESET).addClass("affix");
        var t = this.$target.scrollTop(),
            e = this.$element.offset();
        return this.pinnedOffset = e.top - t
    }, n.prototype.checkPositionWithEventLoop = function() {
        setTimeout(t.proxy(this.checkPosition, this), 1)
    }, n.prototype.checkPosition = function() {
        if (this.$element.is(":visible")) {
            var e = this.$element.height(),
                i = this.options.offset,
                o = i.top,
                r = i.bottom,
                s = t(document.body).height();
            "object" != typeof i && (r = o = i), "function" == typeof o && (o = i.top(this.$element)), "function" == typeof r && (r = i.bottom(this.$element));
            var a = this.getState(s, e, o, r);
            if (this.affixed != a) {
                null != this.unpin && this.$element.css("top", "");
                var l = "affix" + (a ? "-" + a : ""),
                    c = t.Event(l + ".bs.affix");
                if (this.$element.trigger(c), c.isDefaultPrevented()) return;
                this.affixed = a, this.unpin = "bottom" == a ? this.getPinnedOffset() : null, this.$element.removeClass(n.RESET).addClass(l).trigger(l.replace("affix", "affixed") + ".bs.affix")
            }
            "bottom" == a && this.$element.offset({
                top: s - e - r
            })
        }
    };
    var i = t.fn.affix;
    t.fn.affix = e, t.fn.affix.Constructor = n, t.fn.affix.noConflict = function() {
        return t.fn.affix = i, this
    }, t(window).on("load", function() {
        t('[data-spy="affix"]').each(function() {
            var n = t(this),
                i = n.data();
            i.offset = i.offset || {}, null != i.offsetBottom && (i.offset.bottom = i.offsetBottom), null != i.offsetTop && (i.offset.top = i.offsetTop), e.call(n, i)
        })
    })
}(jQuery),
function(t) {
    function e(e) {
        var n = t(e);
        n.prop("disabled") || n.closest(".form-group").addClass("is-focused")
    }

    function n(n) {
        n.closest("label").hover(function() {
            var n = t(this).find("input");
            n.prop("disabled") || e(n)
        }, function() {
            i(t(this).find("input"))
        })
    }

    function i(e) {
        t(e).closest(".form-group").removeClass("is-focused")
    }
    t.expr[":"].notmdproc = function(e) {
        return !t(e).data("mdproc")
    }, t.material = {
        options: {
            validate: !0,
            input: !0,
            ripples: !0,
            checkbox: !0,
            togglebutton: !0,
            radio: !0,
            arrive: !0,
            autofill: !1,
            withRipples: [".btn:not(.btn-link)", ".card-image", ".navbar a:not(.withoutripple)", ".footer a:not(.withoutripple)", ".dropdown-menu a", ".nav-tabs a:not(.withoutripple)", ".withripple", ".pagination li:not(.active):not(.disabled) a:not(.withoutripple)"].join(","),
            inputElements: "input.form-control, textarea.form-control, select.form-control",
            checkboxElements: ".checkbox > label > input[type=checkbox]",
            togglebuttonElements: ".togglebutton > label > input[type=checkbox]",
            radioElements: ".radio > label > input[type=radio]"
        },
        checkbox: function(e) {
            n(t(e || this.options.checkboxElements).filter(":notmdproc").data("mdproc", !0).after("<span class='checkbox-material'><span class='check'></span></span>"))
        },
        togglebutton: function(e) {
            n(t(e || this.options.togglebuttonElements).filter(":notmdproc").data("mdproc", !0).after("<span class='toggle'></span>"))
        },
        radio: function(e) {
            n(t(e || this.options.radioElements).filter(":notmdproc").data("mdproc", !0).after("<span class='circle'></span><span class='check'></span>"))
        },
        input: function(e) {
            t(e || this.options.inputElements).filter(":notmdproc").data("mdproc", !0).each(function() {
                var e = t(this),
                    n = e.closest(".form-group");
                0 === n.length && (e.wrap("<div class='form-group'></div>"), n = e.closest(".form-group")), e.attr("data-hint") && (e.after("<p class='help-text'>" + e.attr("data-hint") + "</p>"), e.removeAttr("data-hint"));
                if (t.each({
                        "input-lg": "form-group-lg",
                        "input-sm": "form-group-sm"
                    }, function(t, i) {
                        e.hasClass(t) && (e.removeClass(t), n.addClass(i))
                    }), e.hasClass("floating-label")) {
                    var i = e.attr("placeholder");
                    e.attr("placeholder", null).removeClass("floating-label");
                    var o = e.attr("id"),
                        r = "";
                    o && (r = "for='" + o + "'"), n.addClass("label-floating"), e.after("<label " + r + "class='control-label'>" + i + "</label>")
                }(null === e.val() || "undefined" == e.val() || "" === e.val()) && n.addClass("is-empty"), n.append("<span class='material-input'></span>"), n.find("input[type=file]").length > 0 && n.addClass("is-fileinput")
            })
        },
        attachInputEventHandlers: function() {
            var n = this.options.validate;
            t(document).on("change", ".checkbox input[type=checkbox]", function() {
                t(this).blur()
            }).on("keydown paste", ".form-control", function(e) {
                (function(t) {
                    return void 0 === t.which || "number" == typeof t.which && t.which > 0 && !t.ctrlKey && !t.metaKey && !t.altKey && 8 != t.which && 9 != t.which && 13 != t.which && 16 != t.which && 17 != t.which && 20 != t.which && 27 != t.which
                })(e) && t(this).closest(".form-group").removeClass("is-empty")
            }).on("keyup change", ".form-control", function() {
                var e = t(this),
                    i = e.closest(".form-group"),
                    o = void 0 === e[0].checkValidity || e[0].checkValidity();
                "" === e.val() ? i.addClass("is-empty") : i.removeClass("is-empty"), n && (o ? i.removeClass("has-error") : i.addClass("has-error"))
            }).on("focus", ".form-control, .form-group.is-fileinput", function() {
                e(this)
            }).on("blur", ".form-control, .form-group.is-fileinput", function() {
                i(this)
            }).on("change", ".form-group input", function() {
                var e = t(this);
                if ("file" != e.attr("type")) {
                    var n = e.closest(".form-group");
                    e.val() ? n.removeClass("is-empty") : n.addClass("is-empty")
                }
            }).on("change", ".form-group.is-fileinput input[type='file']", function() {
                var e = t(this).closest(".form-group"),
                    n = "";
                t.each(this.files, function(t, e) {
                    n += e.name + ", "
                }), (n = n.substring(0, n.length - 2)) ? e.removeClass("is-empty") : e.addClass("is-empty"), e.find("input.form-control[readonly]").val(n)
            })
        },
        ripples: function(e) {
            t(e || this.options.withRipples).ripples()
        },
        autofill: function() {
            var e = setInterval(function() {
                t("input[type!=checkbox]").each(function() {
                    var e = t(this);
                    e.val() && e.val() !== e.attr("value") && e.trigger("change")
                })
            }, 100);
            setTimeout(function() {
                clearInterval(e)
            }, 1e4)
        },
        attachAutofillEventHandlers: function() {
            var e;
            t(document).on("focus", "input", function() {
                var n = t(this).parents("form").find("input").not("[type=file]");
                e = setInterval(function() {
                    n.each(function() {
                        var e = t(this);
                        e.val() !== e.attr("value") && e.trigger("change")
                    })
                }, 100)
            }).on("blur", ".form-group input", function() {
                clearInterval(e)
            })
        },
        init: function(e) {
            this.options = t.extend({}, this.options, e);
            var n = t(document);
            t.fn.ripples && this.options.ripples && this.ripples(), this.options.input && (this.input(), this.attachInputEventHandlers()), this.options.checkbox && this.checkbox(), this.options.togglebutton && this.togglebutton(), this.options.radio && this.radio(), this.options.autofill && (this.autofill(), this.attachAutofillEventHandlers()), document.arrive && this.options.arrive && (t.fn.ripples && this.options.ripples && n.arrive(this.options.withRipples, function() {
                t.material.ripples(t(this))
            }), this.options.input && n.arrive(this.options.inputElements, function() {
                t.material.input(t(this))
            }), this.options.checkbox && n.arrive(this.options.checkboxElements, function() {
                t.material.checkbox(t(this))
            }), this.options.radio && n.arrive(this.options.radioElements, function() {
                t.material.radio(t(this))
            }), this.options.togglebutton && n.arrive(this.options.togglebuttonElements, function() {
                t.material.togglebutton(t(this))
            }))
        }
    }
}(jQuery),
function(t, e, n, i) {
    "use strict";

    function o(e, n) {
        s = this, this.element = t(e), this.options = t.extend({}, a, n), this._defaults = a, this._name = r, this.init()
    }
    var r = "ripples",
        s = null,
        a = {};
    o.prototype.init = function() {
        var n = this.element;
        n.on("mousedown touchstart", function(i) {
            if (!s.isTouch() || "mousedown" !== i.type) {
                n.find(".ripple-container").length || n.append('<div class="ripple-container"></div>');
                var o = n.children(".ripple-container"),
                    r = s.getRelY(o, i),
                    a = s.getRelX(o, i);
                if (r || a) {
                    var l = s.getRipplesColor(n),
                        c = t("<div></div>");
                    c.addClass("ripple").css({
                        left: a,
                        top: r,
                        "background-color": l
                    }), o.append(c), e.getComputedStyle(c[0]).opacity, s.rippleOn(n, c), setTimeout(function() {
                        s.rippleEnd(c)
                    }, 500), n.on("mouseup mouseleave touchend", function() {
                        c.data("mousedown", "off"), "off" === c.data("animating") && s.rippleOut(c)
                    })
                }
            }
        })
    }, o.prototype.getNewSize = function(t, e) {
        return Math.max(t.outerWidth(), t.outerHeight()) / e.outerWidth() * 2.5
    }, o.prototype.getRelX = function(t, e) {
        var n = t.offset();
        return s.isTouch() ? 1 === (e = e.originalEvent).touches.length && e.touches[0].pageX - n.left : e.pageX - n.left
    }, o.prototype.getRelY = function(t, e) {
        var n = t.offset();
        return s.isTouch() ? 1 === (e = e.originalEvent).touches.length && e.touches[0].pageY - n.top : e.pageY - n.top
    }, o.prototype.getRipplesColor = function(t) {
        return t.data("ripple-color") ? t.data("ripple-color") : e.getComputedStyle(t[0]).color
    }, o.prototype.hasTransitionSupport = function() {
        var t = (n.body || n.documentElement).style;
        return t.transition !== i || t.WebkitTransition !== i || t.MozTransition !== i || t.MsTransition !== i || t.OTransition !== i
    }, o.prototype.isTouch = function() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
    }, o.prototype.rippleEnd = function(t) {
        t.data("animating", "off"), "off" === t.data("mousedown") && s.rippleOut(t)
    }, o.prototype.rippleOut = function(t) {
        t.off(), s.hasTransitionSupport() ? t.addClass("ripple-out") : t.animate({
            opacity: 0
        }, 100, function() {
            t.trigger("transitionend")
        }), t.on("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function() {
            t.remove()
        })
    }, o.prototype.rippleOn = function(t, e) {
        var n = s.getNewSize(t, e);
        s.hasTransitionSupport() ? e.css({
            "-ms-transform": "scale(" + n + ")",
            "-moz-transform": "scale(" + n + ")",
            "-webkit-transform": "scale(" + n + ")",
            transform: "scale(" + n + ")"
        }).addClass("ripple-on").data("animating", "on").data("mousedown", "on") : e.animate({
            width: 2 * Math.max(t.outerWidth(), t.outerHeight()),
            height: 2 * Math.max(t.outerWidth(), t.outerHeight()),
            "margin-left": -1 * Math.max(t.outerWidth(), t.outerHeight()),
            "margin-top": -1 * Math.max(t.outerWidth(), t.outerHeight()),
            opacity: .2
        }, 500, function() {
            e.trigger("transitionend")
        })
    }, t.fn.ripples = function(e) {
        return this.each(function() {
            t.data(this, "plugin_" + r) || t.data(this, "plugin_" + r, new o(this, e))
        })
    }
}(jQuery, window, document);