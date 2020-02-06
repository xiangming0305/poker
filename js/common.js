var App = {

    init: function () {
        _.Templates.add("tournamentsTemplate")
        this.FrameRequest.init();
        this.Tournaments.init();
        this.CashOut.init()
        this.History.init()
        this.Deposit.init();
        this.Forms.init();
        this.Partners.init();
        this.Footer.init();
    }

    , FrameRequest: {
        init: function () {
            _.$("#frameRequest").click(this.onClick)
        }

        , onClick: function () {

            _.$(".frameRequest .status").HTML("<i class='info'>Sending request...</i>");
            _.postJSON("/profile/modules/frameRequest.php", {}, function (data) {
                _.$(".frameRequest .status").HTML("<i class='ok'>" + data + "</i>");
            }, function (err) {
                _.$(".frameRequest .status").HTML("<i class='error'>" + err + "</i>");
            }, function (e) {
                console.log(e)
            })
        }
    }

    , Partners: {
        init: function () {
            _.$("[href='#partnership']").click(function () {
                _.$("#partnership").display("block");
            })
            _.$("#partnership").click(function (e) {
                if (e.target == this) {
                    _.$(this).display("none");
                }
            })
            _.$("#partnership input, #partnership textarea").click(function () {
                if (this.select) {
                    this.select()
                }
            })
            _.$("#partnership .copy").click(function () {

                document.getSelection().removeAllRanges();
                _.$(this).parent(2).find("input, textarea")[0].select()
                console.log(_.$(this).parent(2).find("input, textarea"));
                if (document.execCommand("copy")) {
                    _.$("#partnership .copy").val = "  Copy  ";
                    _.$(this).val = "Copied!";
                }
            })
        }
    }

    , Forms: {
        init: function () {

            _.$("#login").click(function () {
                _.$("#popup, #login_form").display("block");
            })

            _.$("#register").click(function () {
                _.$("#popup,#register_form").display("block");
            })

            _.$("#forget_password").click(function () {
                _.$("#popup,#login_form").display("none");
                _.$("#popup,#forget_password_form").display("block");
            })

            _.$("#closeForm, .popup, #popup").click(function () {
                _.$("#popup, #popup>*, .popup").display("none")
            })

            _.$("#popup>*, .popup>*").click(function (e) {
                e.stopPropagation();
            })

            _.$("#register_form").submit(function (e) {
                e.preventDefault();
                var path = _.$(this).attr("action");


                _.$("#register_form .status").HTML("<i class='info'>Processing...</i>");
                _.new("div").post(path, {
                    playername: _.$("#register_form [name='playername']").val
                    , email: _.$("#register_form [name='email']").val
                    , password: _.$("#register_form [name='password']").val
                    , confirmpassword: _.$(" #register_form [name='confirmpassword']").val
                    , realname: _.$("#register_form [name='realname']").val
                    , referral: _.$("#register_form [name='affiliatecode']").val
                    , location: _.$("#register_form [name='location']").val
                    , sex: _.$("#register_form [name='sex']:checked").val
                    , referral_level: _.$("#register_form [name='level']").val
                    , captcha_code: _.$("#register_form [name='captcha_code']").val
					, ip: _.$("#register_form [name='ip']").val
                }, function (r) {
                    var d;
                    try {
                        d = JSON.parse(r)
                    } catch (e) {
                        console.error(r);
                        return;
                    }
                    if (d.status.toUpperCase() == "ERROR") {
                        _.$("#register_form .status").HTML("<i class='error'>" + d.message + "</i>");
                        document.getElementById('captcha').src = '/libs/securimage/securimage_show.php?' + Math.random();
                        return false
                    }

                    if (d.status.toLowerCase() == "ok") {
                        _.$("#register_form .status").HTML("<i class='ok'>" + d.data + "</i>");
                        setTimeout(function () {
                            location.assign("/profile");
                        }, 1500);
                    }

                })
            })

            _.$("#login_form").submit(function (e) {

                e.preventDefault();
                var path = _.$(this).attr("action");

                _.$("#login_form .status").HTML("<i class='info'>Processing...</i>");

                _.postJSON(path, {
                    playername: _.$("#login_form #playername").val
                    , password: _.$("#login_form #password").val
                }, function (data) {
                    _.$("#login_form .status").HTML("<i class='ok'>" + data + "</i>");
                    setTimeout(function () {
                        location.href = "/game";
                    }, 0)
                }, function (message) {
                    _.$("#login_form .status").HTML("<i class='error'>" + message + "</i>");
                }, function (response) {
                    _.$("#login_form .status").HTML("<i class='error'> An error occured while executing your response! </i>");
                    console.error(response);
                })
            })

            _.$("#forget_password_form").submit(function (e) {

                e.preventDefault();
                var path = _.$(this).attr("action");

                _.$("#forget_password_form .status").HTML("<i class='info'>Processing...</i>");

                _.postJSON(path, {
                    email: _.$("#forget_password_form #email").val,
                }, function (data) {
                    _.$("#forget_password_form .status").HTML("<i class='ok'>" + data + "</i>");
                }, function (message) {
                    _.$("#forget_password_form .status").HTML("<i class='error'>" + message + "</i>");
                }, function (response) {
                    _.$("#forget_password_form .status").HTML("<i class='error'> An error occured while executing your response! </i>");
                    console.error(response);
                })
            })
        }
    }
    , Tournaments: {
        init: function () {

            _.$("#tournamentsPart").HTML("<tr><td colspan='5'><p class='spinner_small'></p>Retrieving tournament list...</td></tr>");
            _.$("[href='#freeroll']").click(this.onOpen);
            _.$("#freeroll").click(function (e) {
                this.style.display = "none";
            })
            _.$("#freeroll>*").click(function (e) {
                e.stopPropagation();
            })
            this.load();

        }

        , onOpen: function (e) {
            e.stopPropagation();
            e.preventDefault();
            _.$("#freeroll").display("block");
        }

        , load: function () {
            _.postJSON("/views/tournamentList.php", {}, function (d) {
                _.$("#tournamentsPart").fromTemplate("tournamentsTemplate", d);
                _.$("#tournamentsPart td[data-accepted]").forEach(function (e) {
                    if (e.data("accepted") == 1) {
                        e.find(".unregister, .unauthorized, .nenough").remove();
                    }
                    if (e.data("accepted") == 0) {
                        e.find(".register, .unauthorized, .nenough").remove()
                    }
                    if (e.data("accepted") == -1) {
                        e.find(".register, .unregister, .nenough").remove();
                    }
                    if (e.data("accepted") == -2) {
                        e.find(".register, .unregister, .unauthorized").remove()
                    }
                })
                App.Tournaments.handle();
            }, function (e) {
                console.error(e);
            }, function (r) {
                console.error(r);
            })
        }

        , handle: function () {
            _.$("#tournamentsPart input.register").click(this.onRegister);
            _.$("#tournamentsPart input.unregister").click(this.onUnregister);
        }

        , onRegister: function () {
            var name = _.$(this).parent(3)[0].children[0].innerText.trim();
            var p = this.parentNode;
            this.outerHTML = "<p class='spinner_small'></p>";
            _.postJSON("/views/tournamentRegister.php", {tournament: name}, function (d) {

                //_.$(p).HTML(d);
                setTimeout(function () {
                    App.Tournaments.init()
                }, 1000)
                console.log(d);
            }, function (e) {
                alert(e);
                setTimeout(function () {
                    App.Tournaments.init()
                }, 1000)
            }, function (r) {
                console.error(r);
            })
        }

        , onUnregister: function () {
            var name = _.$(this).parent(3)[0].children[0].innerText.trim();
            var p = this.parentNode;
            this.outerHTML = "<p class='spinner_small'></p>";
            console.log({tournament: name});
            _.postJSON("/views/tournamentUnregister.php", {tournament: name}, function (d) {

                //_.$(p).HTML(d);
                setTimeout(function () {
                    App.Tournaments.init()
                }, 1000)
                console.log(d);
            }, function (e) {
                alert(e);
            }, function (r) {
                console.error(r);
            })
        }
    }

    , Deposit: {
        init: function () {
            this.paymentMethodChange();
            _.$("a#depositLink").click(function (e) {
                e.preventDefault();
                _.$("#requestDeposit").display("block");

            })
            _.$("#deposit").click(this.onClick);
            _.$('#depositAmount').change(this.moneyChange);
            $('#depositPaymentMethod').on('change', this.paymentMethodChange);
        }

        , paymentMethodChange: function () {
            var selected = $(this).find(':selected');
            $('.payment-desc').html(selected.data('desc') || null);
        }

        , moneyChange: function () {
            var money = parseFloat(this.value);
            var rate = parseFloat(_.$('#depositRate').val);
            var chips = money * rate;
            _.$('#depositChip')[0].value = chips;
        }
        , onClick: function () {

            var amo = _.$("#depositAmount").val;
            _.$("#depositStatus").HTML("<i class='info'>Sending request...</i>");
            _.postJSON("/profile/modules/deposit.php", {
                amount: amo,
                payment_method: $('#depositPaymentMethod').val(),
                paid_from: $('#depositPaidFrom').val(),
                paid_from_account: $('#depositPaidFromAccount').val(),
                paid_from_country: $('#depositPaidFromCountry').val(),
                transaction_id: $('#depositTransactionId').val(),
            }, function (d) {
                _.$("#depositStatus").HTML("<i class='ok'>Request sent successfully!</i>");
                _.$("#balanceAmount, #balanceAmountDep").HTML(d);
            }, function (e) {
                _.$("#depositStatus").HTML("<i class='error'>" + e + "</i>");
            }, function (r) {
                _.$("#depositStatus").HTML("<i class='error'>An unexpected error occured!</i>");
                console.error(r);
            })
        }
    }
    , CashOut: {
        init: function () {

			this.paymentMethodChange();
            _.$("a#cashinLink").click(function (e) {
                e.preventDefault();
                _.$("#balance").display("block");

            })
			_.$("#cashin").click(this.onClick);
			$('#cashOutPaymentMethod').on('change', this.paymentMethodChange);
        }
		, paymentMethodChange: function () {
			var selected = $(this).find(':selected');
			$('.payment-desc-cash').html(selected.data('desc') || null);
		}
        , onClick: function (e) {
            var sum = _.$("#chips").val;
			var payment_method = $('#cashOutPaymentMethod').val();
			var receive_by = $('#cashOutBy').val();
			var receive_by_account = $('#cashOutByAccount').val();
			var receive_by_country = $('#cashOutByCountry').val();
            _.$("#balanceStatus").HTML("<i class='info'>Proceeding...</i>");
			_.postJSON("/profile/modules/cashOut.php", {
				sum: sum,
				payment_method: payment_method,
				receive_by: receive_by,
				receive_by_account: receive_by_account,
				receive_by_country: receive_by_country
			}, function (d) {
                _.$("#balanceStatus").HTML("<i class='ok'>Request sent successfully!</i>");
                _.$("#balanceAmount, #balanceAmountDep").HTML(d);
            }, function (e) {
                _.$("#balanceStatus").HTML("<i class='error'>" + e + "</i>")
            }, function (r) {
                _.$("#balanceStatus").HTML("<i class='error'>An unexpected error occured!</i>")
                console.error(r);
            })
        }
    }
    , History: {
        init: function () {
            _.Templates.add("transferTemplate");
            _.$("a#historyLink").click(function (e) {
                e.preventDefault();
                _.$("#transfersHistory").display("block");
                var user = _.$("a#historyLink").attr('data-name');
                App.History.load(user);

            })
        },
        load: function (user) {
            _.postJSON("/admin/modules/transferHistory.php", {user: user}, function (d) {
				_.$("#transfersHistory .spinner_big").display("none");
				var income = [];
				for (var i = 0; i < d.income.length; i++) {
					if (d.income[i][1] != "[[Admin]]")
						income.push(d.income[i]);
				}
                _.$("#transfersHistory #historyIncome tbody").fromTemplate("transferTemplate", income);
                _.$("#transfersHistory #historyOutcome tbody").fromTemplate("transferTemplate", d.outcome);

				_.$("#transfersHistory #historyIncome").display("table");
				_.$("#transfersHistory #historyOutcome").display("none");
				_.$("#incomesTab").addClass("active");
				_.$("#outcomesTab").removeClass("active");
                _.$("#transfersHistory nav a").click(function (e) {
                    e.preventDefault();
                    _.$("#transfersHistory table").display("none");
                    _.$("#transfersHistory nav a").removeClass("active");
                    _.$(this).addClass("active");
                })

				_.$("#outcomesTab").click(function (e) {
					_.$("#transfersHistory #historyOutcome").display("table");
					_.$("#transfersHistory #historyIncome").display("none");
				})

				_.$("#incomesTab").click(function (e) {
					_.$("#transfersHistory #historyOutcome").display("none");
					_.$("#transfersHistory #historyIncome").display("table");
				})

            }, function (e) {
                console.error(e)
            }, function (r) {
                console.error(r)
            })
        }

    }
    , Footer: {
        init: function () {
            _.$('.footer-page').click(this.showPage);
            if ($('#profile').html() || '') {
                this.countMessages();
                setInterval(this.countMessages, 30 * 1000);
            }
        }
        , showPage: function (e) {
            e.preventDefault();
            var targetId = this.getAttribute('data-target');
            _.$('#popup, ' + targetId).display("block");
        }
        ,countMessages: function () {
            $.ajax({
                url: '/profile/contact.php',
                type: 'POST',
                data: {
                    count_message: 1
                },
                success: function (res) {
                    if (res > 0) {
                        $('.message-count').html('(' + res + ')');
                    } else {
                        $('.message-count').html('');
                    }
                }
            })
        }
    }
}

_.core(function () {
    App.init();
})

