A = {

	init: function () {
		A.Data.Routers = {
			"about-us": {
				path: "/views/aboutUs.php"
				, handler: A.Actions.AboutUs
			},
			"payment-method": {
				path: "/views/paymentMethod.php"
				, handler: A.Actions.PaymentMethod
			},
			"poker-rules": {
				path: "/views/pokerRules.php"
				, handler: A.Actions.PokerRules
			},
			"privacy-and-policy": {
				path: "/views/privacyAndPolicy.php"
				, handler: A.Actions.PrivacyAndPolicy
			},
			"terms-and-condition": {
				path: "/views/termsAndCondition.php"
				, handler: A.Actions.TermsAndCondition
			},
			"contact": {
				path: "/views/contact.php"
				, handler: A.Actions.Contact
			}
		};
		_.$(window).hashchange(A.Actions.onHashChange);
		A.Actions.onHashChange();
	}

	, Data: {
		Routers: {}
	}

	, Actions: {
		onHashChange: function () {
			var hash = window.location.hash;
			_.$("#popup").display("none");
			_.$("#popup form").display("none");
			for (var i in A.Data.Routers) {
				if ("#" + i == hash) {
					A.Actions.loadFragment(A.Data.Routers[i]);
					_.$("#" + i).display("none");
				}
			}
		}
		, loadFragment: function (hash) {
			_.$("main").HTML("<p class='spinner_big'></p>").post(hash.path, {}, function (r) {
				hash.handler.init(r);
			});

		}
		, AboutUs: {
			init: function () {
			}
		}
		, PaymentMethod: {
			init: function () {
			}
		}
		, PokerRules: {
			init: function () {
			}
		}
		, PrivacyAndPolicy: {
			init: function () {
			}
		}
		, TermsAndCondition: {
			init: function () {
			}
		}
		, Contact: {
			init: function () {
			}
		}
	}
}
_.core(function () {
	A.init();
	viewTournamentResult = function (id) {
		_.$("#tournamentResultView").display("block");
		_.$("#tournamentResultView .wrap .container").HTML("<p class='spinner_big'></p>");

		_.$("#tournamentResultView .wrap").get("/profile/modules/tournament-result.php", { tournament_id: id })
	}
})