A = {
    
    init: function(){
        A.Data.Routers = {
            "frameRequests": {
                path: "/admin/modules/frameRequests.php"
                ,handler: A.Actions.FrameRequests
            }
            ,"rakeHistory": {
                path: "/admin/modules/rakeHistory.php"
                ,handler: A.Actions.RakeHistory
            }
            
            ,"affiliates":{
                path: "/admin/modules/manageAffiliates.php"
                ,handler: A.Actions.Affiliates
            }
            
            ,"affiliateRequests":{
                path: "/admin/modules/affiliateRequests.php"
                ,handler: A.Actions.AffiliateRequests
            }
            ,"affiliateBalanceRequests":{
                path: "/admin/modules/affiliateBalanceRequests.php"
                ,handler: A.Actions.AffiliateBalanceRequests
            }
            ,"adminWidthdrawRequests":{
                path: "/admin/modules/adminWidthdrawRequests.php"
                ,handler: A.Actions.AdminWidthdrawRequests
            }
            ,"tournamentFees":{
                path: "/admin/modules/tournamentFees.php"
                ,handler: A.Actions.Tournaments
            }
            ,"buyinTournaments":{
                path: "/admin/modules/zeroBuyIn.php"
                ,handler: null
            }
            ,"variables":{
                path: "/admin/modules/variables.php"
                ,handler: A.Actions.Variables
            }
            ,"currenttournaments":{
                path: "/admin/modules/currentTournaments.php"
                ,handler: A.Actions.CurrentTournaments
            }
            ,"ticket":{
                path:"/admin/modules/ticket.php"
                ,handler: A.Actions.TicketTournaments
            }
            ,"cashout":{
                path: "/admin/modules/cashout.php"
                ,handler: A.Actions.Cashout
            }
            ,"deposits":{
                path: "/admin/modules/deposits.php"
                ,handler: A.Actions.Deposits
            }
            ,"inbox":{
                path: "/admin/modules/inbox.php"
                ,handler: A.Actions.Inbox
            }
            ,"smtpSettings": {
                path: "/admin/modules/smtpSettings.php",
                handler: A.Actions.SMTPSettings
            }
            ,"tournamentRequests": {
                path: "/admin/modules/tournamentRequests.php",
                handler: A.Actions.TournamentRequests
            }
            ,"paymentMethods": {
                path: '/admin/modules/paymentMethods.php',
                handler: A.Actions.PaymentMethods
            }
			,"chipTransactions": {
				path: '/admin/modules/chipTransactions.php',
				handler: A.Actions.ChipTransactions
            }
			,"tournamentRegistrations": {
				path: '/admin/modules/tournamentRegistrations.php',
				handler: A.Actions.TournamentRegistrations
            }
        };
        _.$(window).hashchange(A.Actions.onHashChange);
		A.Actions.onHashChange();
	}
	, interval: 0
    
    ,Data: {
        Routers:{}
    }
    
    ,Actions:{
        onHashChange: function(){
            var hash = window.location.hash;
            for (var i in A.Data.Routers){
                if("#"+i == hash){
                    _.$("a[href^='#']").removeClass("active");
                    _.$("a[href='#"+i+"']").addClass("active");
                    A.Actions.loadFragment(A.Data.Routers[i]);
                }
            }
		}
        ,loadFragment: function(hash){
            _.$("#content").HTML("<p class='spinner_big'></p>").post(hash.path,{},function(r){
                hash.handler.init(r);
            });
            
        }
        
        ,Variables:{
            init: function(){
                _.$("#variables input").change(this.onChange);
				_.$("#variables select").change(this.onChange)
            }
            
            ,interval: 0
            
            ,onChange: function(e){
                var t =  _.$(this);
                
                var id = t[0].id;
                var value = t.val;
                if (t[0].type === 'checkbox') {
                    value = t[0].checked ? 1 : 0;
                }
                console.log(value);

                clearTimeout(A.Actions.Variables.interval);
                A.Actions.Variables.interval = setTimeout(function(){
                    t[0].disabled = true;
					_.postJSON("/admin/modules/changeVar.php",{variable: id, value: value },function(d){
                        t[0].disabled = false;
                    }, function(e){
                        console.error(e);
                    }, function(r){
                        console.error(r);
                    })
                },100)
                
                
            }
        }
        
        ,FrameRequests:{
            init: function(){
                _.$(".frameRequests td .accept").click(this.onAccept);
                _.$(".frameRequests td .decline").click(this.onDecline)
            }
            
            ,onAccept: function(e){
                var id = _.$(this).parent(2).data("id");
                A.Actions.FrameRequests.change({id: id, status: "accepted"})
            }
            
            ,onDecline: function(e){
                var id = _.$(this).parent(2).data("id");
                A.Actions.FrameRequests.change({id: id, status: "declined"})
            }
            
            ,change: function(o){
                var target = _.$(".frameRequests tr[data-id='"+o.id+"'] td:last-child");
                _.postJSON("/admin/modules/frameRequestStatus.php",o, function(d){
                    target.HTML(d);
                }, function(m){
                    target.HTML("<p class='status'><i class='error'>"+m+"</i></p>");
                }, function(r){
                    target.HTML("<p class='status'><i class='error'>An error occured while changing status!</i></p>");
                    console.error(r);
                })
            }
        }
        
        ,AffiliateRequests:{
            init: function(){
                _.$(".affiliateRequests td .accept").click(this.onAccept);
                _.$(".affiliateRequests td .decline").click(this.onDecline)
                this.Details.init();
                _.$("#affiliateDetails").click(function(){_.$(this).display("none")});
            }
            
            ,onAccept: function(e){
                var id = _.$(this).parent(2).data("id");
                A.Actions.AffiliateRequests.change({id: id, status: "accepted"})
            }
            
            ,onDecline: function(e){
                var id = _.$(this).parent(2).data("id");
                A.Actions.AffiliateRequests.change({id: id, status: "declined"})
            }

            ,Details:{
                init: function(){
                    _.$(".affiliateRequests .details").click(this.onOpen)
                }

                ,onOpen: function(){
                    var id = _.$(this).data("id");
                    _.$("#affiliateDetails").display("block");
                    _.postJSON("/admin/modules/affiliateRequestDetails.php",{id:id}, function(d){
                        console.log(d['data']);
                        _.$("#affiliateDetails .wrap").HTML('<b>Company : </b>' + d.company + '<br/><b>Position : </b>' + d.position + '<br/><b>Country : </b>' + d.country + '<br/><b>How did you hear about us? : </b>' + d.hear_about_us + '<br/><b>How you will bring players? : </b>' + d.how_bring_players + '<br/><b>How many players can you bring? : </b>' + d.how_many_players);
                    },function(m){
                        console.error(m);
                    },function(r){
                        console.error(r)
                    })
                }
            }
            
            ,change: function(o){
                var target = _.$(".affiliateRequests tr[data-id='"+o.id+"'] td.st");
                _.postJSON("/admin/modules/affiliateRequestStatus.php",o, function(d){
                    target.HTML(d);
                }, function(m){
                    target.HTML("<p class='status'><i class='error'>"+m+"</i></p>");
                }, function(r){
                    target.HTML("<p class='status'><i class='error'>An error occured while changing status!</i></p>");
                    console.error(r);
                })
            }
        }

        ,AffiliateBalanceRequests:{
            init: function(){
                _.$(".affiliateRequests td .accept").click(this.onAccept);
                _.$(".affiliateRequests td .decline").click(this.onDecline);
            }

            ,onAccept: function(e){
                var id = _.$(this).parent(2).data("id");
                A.Actions.AffiliateBalanceRequests.change({id: id, status: "accepted"})
            }

            ,onDecline: function(e){
                var id = _.$(this).parent(2).data("id");
                A.Actions.AffiliateBalanceRequests.change({id: id, status: "declined"})
            }

            ,change: function(o){
                var target = _.$(".affiliateRequests tr[data-id='"+o.id+"'] td:last-child");
                _.postJSON("/admin/modules/affiliateBalanceRequestStatus.php",o, function(d){
                    target.HTML(d);
                }, function(m){
                    target.HTML("<p class='status'><i class='error'>"+m+"</i></p>");
                }, function(r){
                    target.HTML("<p class='status'><i class='error'>An error occured while changing status!</i></p>");
                    console.error(r);
                })
            }
        }

        ,AdminWidthdrawRequests:{
            init: function(){
                _.$("#transferAffiliate").submit(this.onTransferAffiliate);
            }
            ,onTransferAffiliate: function(e){
                e.preventDefault();
                _.postJSON("/profile/modules/transferBalance.php",{amount: _.$("#affiliate_amount").val,admin:true},
                function(d){
                    _.$("#affiliate-transfer-status").HTML("<i class='ok'>Your request has been sent successfully!</i>");
                    location.search = 'q='+new Date().getTime();
                },function(e){
                    _.$("#affiliate-transfer-status").HTML("<i class='error'>"+e+"</i>");
                },function(r){
                    _.$("#affiliate-transfer-status").HTML("<i class='error'> An unexpected error occured!</i>");
                    console.error(r);
                });
            }
        
        }
        
        ,RakeHistory:{
            init: function(){
        
                _.$("#rakeHistoryHand").click(function(){_.$(this).display("none")});
                _.$("#rakeHistoryHand>*").click(function(e){e.stopPropagation()});
                
                _.$(".rakeHistory li").click(this.openHand);
            }
            
            ,openHand: function(e){
                var id = _.$(this).data("hand");
                
                _.$("#rakeHistoryHand").display("block").find(".wrap").HTML("<p class='spinner_big'></p>");
                _.postJSON("/admin/modules/getHand.php",{hand: id},function(d){
                    var data = JSON.parse(d.history);
                    var s = "";
                    for(var i in data){
                        
                        s+=(i!=0) ? "<p>"+data[i]+"</p>" : "<h3> "+data[i]+"</h3>";
                    }
                    _.$("#rakeHistoryHand .wrap").HTML(s);
                },function(m){
                    _.$("#rakeHistoryHand .wrap").HTML("<p class='status'><i class='error'>"+m+"</i> </p>");
                },function(e){
                    console.error(e);
                    _.$("#rakeHistoryHand .wrap").HTML("<p class='status'><i class='error'>An unexpected error occured. Please, try again later.</i> </p>");
                })
            }
        }
        
        ,Affiliates:{
            
            init: function(r){
                this.users = _.$("#affiliates").data("amount")*1;
                _.$(".affiliatePopup").click(function(e){
                    _.$(this).display("none");
                })
                _.$(".affiliatePopup>*").click(function(e){
                    e.stopPropagation();
                })
                _.E.name="";
                this.Pagination.init();
                
                this.Search.init();
            
            }
            
            ,Search:{
                init: function(){
                    _.$("#searchLine").keyup(this.onPrint);
                }
                
                ,onPrint: function(){
                    var s = _.$(this).val;
                    if(s.trim().length>2){
                        _.E.name = s.trim();
                       
                    }else{
                        _.E.name = "";
                    }
                    A.Actions.Affiliates.Pagination.init();
                }
            }
            ,Details:{
                init: function(){
                    _.$("#affiliates .details").click(this.onOpen)
                    if (!_.E.name) {
                        _.Templates.add("affiliateDetailTemplate");
                    }
                }
                
                ,onOpen: function(){
                    var id = _.$(this).data("id");
                    _.$("#affiliateDetails").display("block");
                    _.postJSON("/admin/modules/affiliateDetails.php",{id:id}, function(d){
                        console.log(d['data']);
                        _.$("#affiliateDetails table tbody").fromTemplate("affiliateDetailTemplate",d['data']);
                        _.$("#affiliateDetails table tbody").appendHTML("<tr><td colspan='2'>Total:</td><td class='number'></td><td class='number'>"+d['sumFee']+"</td><td class='number'>"+d['sum']+"</td></tr>");
                        _.$("#affiliateName").HTML(d['name']);
                    },function(m){
                        console.error(m);
                    },function(r){
                        console.error(r)
                    })
                }
            }
            ,Pagination: {
                init: function(){
                    this.createNavigation();
                    this.openPage(1);
                    
                    if (!_.E.name) {
                        _.Templates.add("user");
                    }
                }
                ,createNavigation: function(){
                    var pages = Math.ceil(A.Actions.Affiliates.users/this.step);
                    this.pages = pages;
                    
                     _.$(".affiliates.pages").HTML("");
                    for(var i = 1; i<=pages; i++){
                         _.$(".affiliates.pages").appendHTML("<li><a href='#' data-n='"+i+"'>"+i+"</a></li>");
                    }
                    _.$(".affiliates.pages a").click(this.onClick);
                }
                ,onClick: function(e){
                    e.preventDefault();
                    var page = _.$(this).data("n");
                    A.Actions.Affiliates.Pagination.openPage(page*1);
                }
                ,step: 20
                ,openPage: function(i){
                    _.postJSON("/admin/modules/users.php",{offset: this.step*(i-1), step:this.step, name: _.E.name},
                    function(d){
                        
                        _.$("#affiliates tbody").fromTemplate("user", d);
                        A.Actions.Affiliates.Settings.init();
                        A.Actions.Affiliates.Pagination.setActive(i);
                        A.Actions.Affiliates.Details.init();
                        A.Actions.Affiliates.Message.init();
                    },function(m){
                        console.error(e);
                    },function(e){
                        console.error(e);
                    });
                }
                
                ,setActive: function(i){
                    _.$(".affiliates.pages li a").removeClass("active");
                    _.$(".affiliates.pages li a[data-n='"+i+"']").addClass("active");
                    
                }
            }
            
            ,Message: {
                init: function(){
                    _.$("#affiliates .message").click(this.onClick);
                    _.$("#affiliateMessage form").submit(this.onSend)
                    _.$("#sendContact").click(this.onSendContact)
                }
                
                ,onClick: function(e){
                    var id = _.$(this).data("id");
                    _.E.affiliate = id;
                    _.E.affiliateName = _.$(this).data("playername");
                    _.$("#affiliateMessage").display("block")
                }
                
                ,onSend: function(e){
                    e.preventDefault();
                    var s = {
                        id: _.E.affiliate
                        ,message:  _.$("#affiliatemsg").val
                    }
                    _.$("#messageStatus").HTML("<i class='info'>Sending message...</i>");
                    _.postJSON("/admin/modules/sendMessage.php",s,function(d){
                        _.$("#messageStatus").HTML("<i class='ok'>"+d+"</i>");
                    }, function(e){
                        _.$("#messageStatus").HTML("<i class='error'>"+e+"</i>");
                    }, function(r){
                        _.$("#messageStatus").HTML("<i class='error'>An unexpected error occured!</i>");
                        console.error(r);
                    })
                }
                ,onSendContact: function(e){
                    e.preventDefault();
                    _.postJSON("/profile/modules/sendMessage.php",{
                        to_name: _.E.affiliateName,
                        message: _.$("#affiliatemsg").val
                    },function(d){
                        _.$("p.status").HTML("<i class='ok'>"+d+"</i>");
                        location.search = 'q='+new Date().getTime();
                    },function(e){
                        _.$("p.status").HTML("<i class='error'>"+e+"</i>");
                    },function(r){
                        _.$("p.status").HTML("<i class='error'>An unexpected error occured!</i>");
                        console.error(r)
                    });
                }
            }
            
            ,Settings: {
                
                init: function(){
                    _.$("#affiliates input.settings").click(this.onClick);
                     if (!_.E.name) {
                         _.Templates.add("referralTemplate");
                        _.Templates.add("referralAddTemplate");
                     }
                }
                
                ,onClick: function(){
                    _.E.affiliate = _.$(this).data("id");
                    _.$("#affiliateSettings").display("block");
                    _.$("#addReferralForm").display("none");
                    
                    _.postJSON("/admin/modules/userInfo.php",{id: _.$(this).data("id")},function(d){
                        _.$("#affiliateSettings #realname").val = d.realname;
                        _.$("#affiliateSettings #playername").val = d.playername;
                        _.$("#affiliateSettings #country_geolocated").val = d.country_geolocated;
                        _.$("#affiliateSettings #comission").val = d.comission;
                        _.$("#affiliateSettings #level2_comission").val = d.level2_comission;
                        _.$("#affiliateSettings #link2_commission").val = d.link2_commission;
                        _.$("#affiliateSettings #email").val = d.email;
                        _.$("#affiliateSettings #twolevel").val = d.level2;
                        _.$("#affiliateSettings #show_realname")[0].checked = d.show_realname == 1;
                        _.$("#affiliateSettings #show_email")[0].checked = d.show_email == 1;
                        _.$("#affiliateSettings #chipsBalance").HTML(d.balance);
                        _.$("#affiliateSettings #affiliate_balance").HTML(d.affiliateBalance);
                        _.$("#affiliateSettings #points_balance").HTML(d.points_balance);
                        A.Actions.Affiliates.Settings.Balance.init()
                        
                        _.$("#saveAffiliate").click(A.Actions.Affiliates.Settings.onSave);
                        A.Actions.Affiliates.Settings.Referrals.load();
                    }, function(m){
                        console.error(m);
                    }, function(e){
                        console.error(e);
                    })
                }
                
                ,onSave: function(e){
                    _.$("#affiliateStatus").HTML("<i class='info'>Saving...</i>")
                    var s = {
                        realname: _.$("#affiliateSettings #realname").val
                        ,playername: _.$("#affiliateSettings #playername").val
                        ,comission: _.$("#affiliateSettings #comission").val
                        ,link2_commission: _.$("#affiliateSettings #link2_commission").val
                        ,level2_comission: _.$("#affiliateSettings #level2_comission").val
                        ,email:  _.$("#affiliateSettings #email").val
                        ,level2:  _.$("#affiliateSettings #twolevel").val
                        ,show_realname:  _.$("#affiliateSettings #show_realname")[0].checked?1:0
                        ,show_email:  _.$("#affiliateSettings #show_email")[0].checked?1:0
                        ,id: _.E.affiliate
                    }
                    
                    console.log(s);
                    _.postJSON("/admin/modules/saveAffiliate.php",s,function(d){
                        _.$("#affiliateStatus").HTML("<i class='ok'>"+d+"</i>")
                    },function(m){
                        _.$("#affiliateStatus").HTML("<i class='error'>"+m+"</i>")
                    },function(e){
                        _.$("#affiliateStatus").HTML("<i class='error'>An unexpected error occured!</i>");
                        console.error(e);
                    })
                }
                
                ,Balance: {
                    init: function(){
                        _.$("#chips label.dec").addClass("inactive");
                        _.$("#chips label.dec input").click(this.focusDec).focus(this.focusDec);
                        _.$("#chips label.inc input").click(this.focusInc).focus(this.focusInc);
                        A.Actions.Affiliates.Settings.Balance.onInc.call(_.$("#addChips")[0])
                        
                        _.$("#chips #addChips").keyup(this.onInc).change(this.onInc);
                        _.$("#chips #decChips").keyup(this.onDec).change(this.onDec);
                        _.$("#transferChips").click(this.onSubmit);
                        _.$("#saveAffiliateExtra").click(this.saveExtraAffiliate);
                        _.$("#savePointsBalanceExtra").click(this.savePointsBalanceExtra);
                        
                    }
                    
                    ,focusDec: function(){
                        _.$("#chips label.dec").removeClass("inactive");
                        _.$("#chips label.inc").addClass("inactive");
                        A.Actions.Affiliates.Settings.Balance.onDec.call(this)
                    }
                    
                    ,focusInc: function(){
                        _.$("#chips label.inc").removeClass("inactive");
                        _.$("#chips label.dec").addClass("inactive");
                        A.Actions.Affiliates.Settings.Balance.onInc.call(this)
                    }
                    
                    ,onInc: function(e){
                        var amount = this.value*1;
                        _.$("#transferChips").val = "Add "+amount+" chips to player balance";
                        _.$("#transferChips").data("amount",amount)
                    }
                    
                    ,onDec: function(e){
                        var amount = this.value*1;
                        _.$("#transferChips").val = "Deduct "+amount+" chips from player balance";
                        _.$("#transferChips").data("amount", -amount)
                    }
                    
                    ,onSubmit: function(){
                        var amount = _.$(this).data("amount");
                        
                        
                        _.$("#transferStatus").HTML("<i class='info'>Transferring...</i>");
                        _.postJSON("/admin/modules/chipsTransfer.php",{player: _.E.affiliate, amount: amount}, function(d){
                            _.$("#transferStatus").HTML("<i class='ok'>Transfer completed successfully.</i>");
                            _.$("#chipsBalance").HTML(d);
                        }, function(e){
                            _.$("#transferStatus").HTML("<i class='error'>"+e+"</i>")
                        }, function(r){
                            _.$("#transferStatus").HTML("<i class='error'>An unexected error occured!</i>");
                            console.error(r);
                        })
                    }
                    ,saveExtraAffiliate: function(){
                        var amount = _.$('#extra_feature_amount').val;
                        var type = _.$('#extra_feature_type').val;
                        
                        _.$("#affiliateExtra").HTML("<i class='info'>Transferring...</i>");
                        _.postJSON("/admin/modules/extraAffiliateTransfer.php",{player: _.E.affiliate, amount: amount,type: type}, function(d){
                            _.$("#affiliateExtra").HTML("<i class='ok'>Transfer completed successfully.</i>");
                            _.$("#affiliate_balance").HTML(d);
                        }, function(e){
                            _.$("#affiliateExtra").HTML("<i class='error'>"+e+"</i>")
                        }, function(r){
                            _.$("#affiliateExtra").HTML("<i class='error'>An unexected error occured!</i>");
                            console.error(r);
                        })
                    }
                    ,savePointsBalanceExtra: function(){
                        var amount = _.$('#extra_feature_points_amount').val;
                        var type = _.$('#extra_feature_points_type').val;

                        _.$("#pointsBalanceExtra").HTML("<i class='info'>Transferring...</i>");
                        _.postJSON("/admin/modules/pointsBalanceTransfer.php",{player: _.E.affiliate, amount: amount,type: type}, function(d){
                            _.$("#pointsBalanceExtra").HTML("<i class='ok'>Transfer completed successfully.</i>");
                            _.$("#points_balance").HTML(d);
                        }, function(e){
                            _.$("#pointsBalanceExtra").HTML("<i class='error'>"+e+"</i>")
                        }, function(r){
                            _.$("#pointsBalanceExtra").HTML("<i class='error'>An unexected error occured!</i>");
                            console.error(r);
                        })
                    }
                }
                ,Referrals:{
                    load: function(){
                        
                        _.$("#affiliateSettings .referralList tbody").HTML("")
                        _.$("#affiliateSettings .referralList")[0].outerHTML+="<p class='spinner_big'></p>";
                        
                        
                        _.postJSON("/admin/modules/getReferrals.php",{id: _.E.affiliate},function(d){
                            _.$("#affiliateSettings .spinner_big").remove();
                            _.$("#affiliateSettings .referralList tbody").fromTemplate("referralTemplate", d);
                            A.Actions.Affiliates.Settings.Referrals.Add.init();
                            A.Actions.Affiliates.Settings.Referrals.Remove.init();
                            
                        },function(e){
                            console.error(e);
                        }, function(r){
                            console.error(r);
                        })
                    }
                    
                    ,Remove:{
                        init: function(){
                            _.$(".removeReferral").click(this.onClick);
                        }
                        
                        ,onClick: function(e){
                            var ref = _.$(this).data("id");
                            _.postJSON("/admin/modules/removeReferral.php",{ref:ref, from: _.E.affiliate},function(d){
                                A.Actions.Affiliates.Settings.Referrals.load();
                                A.Actions.Affiliates.Settings.Referrals.Add.Loader.onPrint();
                            }, function(e){
                                console.error(e);
                            }, function(r){
                                console.error(r)
                            })
                        }
                    }
                    
                    ,Add:{
                        init: function(){
                            this.Opener.init();
                            this.Loader.init();
                        }
                        
                        ,Opener:{
                            init: function(){
                                this.unevent();
                                _.$("#addReferral").click(this.onClick)
                            }
                            
                            ,unevent: function(){
                                _.$("#addReferral").unevent("click", this.onClick)
                            }
                            
                            ,onClick:function(){
                                (_.$("#addReferralForm").display()=="block") ?
                                _.$("#addReferralForm").display("none") :  (function(){
                                    _.$("#affiliateNameSearch").val="";
                                    _.$("#addReferralForm").display("block")
                                })()
                            }
                        }
                        
                        ,Loader:{
                            init: function(){
                                this.unevent();
                                _.$("#affiliateNameSearch").keyup(this.onPrint)
                            }
                            
                            ,unevent: function(){
                                _.$("#affiliateNameSearch").unevent("keyup", this.onPrint);
                            }
                            
                            ,onPrint: function(e){
                                var text = _.$("#affiliateNameSearch").val.trim();
                                if(text.length>=3){
                                    A.Actions.Affiliates.Settings.Referrals.Add.Loader.proceed(text);
                                }
                            }
                            
                            ,proceed: function(text){
                                var table = _.$("#addReferralForm table");
                                table[0].outerHTML+="<p class='spinner_big'></p>";
                                _.postJSON("/admin/modules/searchUsers.php",{name: text}, function(d){
                                    console.log(d);
                                    _.$("#addReferralForm table tbody").fromTemplate("referralAddTemplate", d);
                                    _.$("#addReferralForm table tbody tr[data-id='"+_.E.affiliate+"']").addClass("inactive");
                                    _.$("#addReferralForm table tbody tr[data-id='"+_.E.affiliate+"'] input").remove()
                                    
                                    _.$("#addReferralForm table tbody tr[data-referral='"+_.E.affiliate+"']").addClass("inactive");
                                    _.$("#addReferralForm table tbody tr[data-referral='"+_.E.affiliate+"'] input").remove()
                                    
                                    
                                    _.$("#addReferralForm .spinner_big").remove();
                                    
                                    A.Actions.Affiliates.Settings.Referrals.Add.List.init();
                                }, function(e){
                                    console.error(e);
                                }, function(r){
                                    console.error(r);
                                })
                            }
                        }
                        
                        ,List: {
                            init: function(){
                                _.$(".submitAddReferral").click(this.onAdd);
                            }
                            
                            ,onAdd: function(e){
                                var id = _.$(this).data('id');
                                _.postJSON("/admin/modules/addAffiliateTo.php",{id: _.E.affiliate, ref: id}, function(d){
                                    A.Actions.Affiliates.Settings.Referrals.load();
                                    A.Actions.Affiliates.Settings.Referrals.Add.Loader.onPrint();
                                    
                                }, function(e){
                                    console.error(e);
                                }, function(r){
                                    console.error(r)
                                })
                            }
                        }
                    }
                    
                    
                }
            }
        }
        
        ,Tournaments:{
            init: function(){
                //_.$("#tournaments .details").click(this.Details.onClick)
            }
            
            
        }
        
        ,CurrentTournaments:{
            init: function(){
                _.$(".entryfee").click(this.onFocus).focus(this.onFocus).change(this.onFocus);
                _.$(".editentryfee").click(this.onClick);
            }
            
            ,onFocus: function(){
                _.$(this).addClass("unsaved")
            }
            
            ,onClick: function(e){
                var trn = _.$(this).data("tournament");
                var fee = _.$(this).parent(2).find(".entryfee.fee").val;
                var restart = _.$(this).parent(2).find(".restart").val
                var enabled = _.$(this).parent(2).find(".enabled")[0].checked ? 1 : 0;
                var latereg = _.$(this).parent(2).find(".latereg").val
                var showentryfee = _.$(this).parent(2).find(".enableentrypoint:checked").val;
                var show = _.$(this).parent(2).find(".show")[0].checked ? 1 : 0;
                _.$(this).parent(2).find(".entryfee").forEach(function(e){
                    e[0].disabled = true;
                });
                var t = this;
                
                this.disabled = true;
                _.postJSON("/admin/modules/changeTournamentFee.php",{tournament: trn, fee: fee, restart: restart, enabled: enabled, latereg: latereg, show_entry_fee: showentryfee, show: show},
                function(d){
                    t.disabled = false;
                    _.$(t).parent(2).find(".entryfee").forEach(function(e){
                        e[0].disabled = false;
                        e.removeClass("unsaved");
                    })
                }, function(e){
                    console.error(e)
                }, function(r){
                    console.error(r)
                })
                
            }
        }
        
        ,TicketTournaments:{
            init: function(){
                _.$("#createTicket").submit(this.onCreate)
                _.Templates.add("ticket_template");
                A.Actions.TicketTournaments.handleList(); 
            }
            
            ,onCreate: function(e){
                e.preventDefault();
                var s = {
                    places: _.$("#ticket_places").val
                    ,tournament: _.$("#ticket_tournament").val
                    ,tournament_for: _.$("#ticket_for").val
                }
                console.log(s);
                
                if(s.tournament == s.tournament_for){
                    _.$("#ticketStatus").HTML("<i class='error'>Tournament and ticket target must be different! </i>");
                    return;
                }
                _.$("#ticketStatus").HTML("<i class='info'>Saving ticket... </i>");
                
                _.postJSON("/admin/modules/createTicket.php",s,function(d){
                    _.$("#ticketStatus").HTML("<i class='ok'>"+d+"</i>");
                    A.Actions.TicketTournaments.reload()
                },function(e){
                    _.$("#ticketStatus").HTML("<i class='error'>"+e+"</i>");
                }, function(r){
                    _.$("#ticketStatus").HTML("<i class='error'>An unexpected error has occured!</i>");
                    console.error(r)
                })
            }
            
            ,reload: function(){
                _.postJSON("/admin/modules/ticketList.php",{}, function(d){
                    _.$("#tickets tbody").fromTemplate("ticket_template",d);
                    A.Actions.TicketTournaments.handleList();
                }, function(e){
                    console.error(e);
                },function(r){
                    console.error(r)
                })
            }
            
            ,handleList: function(){
                _.$("#tickets .removeTicket").click(this.onDelete)
            }
            
            ,onDelete: function(){
                var id = _.$(this).parent(2).data("ticket");
                _.$("#ticketStatus").HTML("<i class='info'>Deleting ticket...</i>");
                _.postJSON("/admin/modules/removeTicket.php",{id:id}, function(d){
                    _.$("#tickets tr[data-ticket='"+id+"']").remove()
                    _.$("#ticketStatus").HTML("<i class='ok'>Ticket removed successfully!</i>");
                }, function(e){
                    console.error(e);
                    _.$("#ticketStatus").HTML("<i class='error'>An error occured while deleting!</i>");
                }, function(r){
                    _.$("#ticketStatus").HTML("<i class='error'>An unexpected error occured while deleting!</i>");
                    console.error(r);
                })
            }
        }
        
        ,Cashout:{
            init: function(){
                _.$("#cashouts .accept").click(this.onAccept);
                _.$("#cashouts .decline").click(this.onDecline);
                this.History.init();
                _.$("input.history").click(this.onHistory);
                this.Amount.init()
            }
            
            ,Amount:{
                init: function(){
                    _.$("tr .editAmount").click(this.onStart)
                }
                
                ,onStart: function(){
                    var amo = _.$(this).parent().find("span").HTML()*1;
                    _.$(this).removeClass("editAmount").addClass("saveAmount");
                    _.$(this).unevent("click", A.Actions.Cashout.Amount.onStart);
                    _.$(this).click(A.Actions.Cashout.Amount.onSave);
                    
                    _.$(this).parent().find("span").HTML("<input type='number' value='"+amo+"'/>");
                }
                
                ,onSave: function(){
                    var amo = _.$(this).parent().find("input").val
                    var id = _.$(this).parent(2).data("id");
                    
                    var t = this;
                    t.disabled = true;
                    _.$(t).parent().find("span input")[0].disabled=true;
                    
                    _.postJSON("/admin/modules/cashoutAmount.php",{amount: amo, id: id}, function(d){
                        
                        t.disabled = false;
                        _.$(t).parent().find("span").HTML(_.$(t).parent().find("span input").val);
                        
                        _.$(t).addClass("editAmount").removeClass("saveAmount").unevent("click", A.Actions.Cashout.Amount.onSave).click(A.Actions.Cashout.Amount.onStart);
                        
                    }, function(e){
                        console.error(e)
                    }, function(r){
                        console.error(r);
                    })
                }
            }
            
            ,onAccept: function(){
				var id = _.$(this).parent(2).data("id");
				var note = $(this).parent().find("#note").val()
				var user = $(this).parent().find("#user_id").val()
				A.Actions.Cashout.change({ id: id, status: 1, note: note, user: user})
            }
            
            ,onDecline: function(){
				var id = _.$(this).parent(2).data("id");
				var note = $(this).parent().find("#note").val()
				var user = $(this).parent().find("#user_id").val()
				A.Actions.Cashout.change({ id: id, status: -1, note: note, user: user})
            }
            
            ,change: function(s){
                _.postJSON("/admin/modules/cashoutStatus.php",s,function(d){
                    _.$("#cashouts tr[data-id='"+s.id+"'] .buttons").HTML("");
                    _.$("#cashouts tr[data-id='"+s.id+"'] .stat").HTML(d);
                },function(e){
                    console.error(e)
                }, function(r){
                    console.error(r);
                })
            }
            
            ,History:{
                init: function(){
					_.Templates.add("transferTemplateAdmin");
                    _.$("td input.history").click(this.onClick);
                    _.$("#transfersHistoryAdmin").click(function(e){
                        if(e.target==this) {
                            _.$(this).display("none");
                        }
                    })
                    
                }
                
                ,onClick: function(e){
                    e.preventDefault()
                    var user = _.$(this).parent(1).find(".user").HTML().trim();
					_.$("#transfersHistoryAdmin").display("block");
                    A.Actions.Cashout.History.load(user);
                
                }
                
                ,load: function(user){
                    _.postJSON("/admin/modules/transferHistory.php",{user: user}, function(d){
						_.$("#transfersHistoryAdmin .spinner_big").display("none");
						var income = [];
						for (var i = 0; i < d.income.length; i++) {
							if (d.income[i][1] != "[[Admin]]")
								income.push(d.income[i]);
						}
						_.$("#transfersHistoryAdmin #historyIncome tbody").fromTemplate("transferTemplateAdmin", income);
						_.$("#transfersHistoryAdmin #historyOutcome tbody").fromTemplate("transferTemplateAdmin", d.outcome);
                        
						_.$("#transfersHistoryAdmin #historyIncome").display("table");
						_.$("#transfersHistoryAdmin #historyOutcome").display("none");
                        
						_.$("#transfersHistoryAdmin nav a").click(function(e){
                             e.preventDefault();
							 _.$("#transfersHistoryAdmin table").display("none");
							 _.$("#transfersHistoryAdmin nav a").removeClass("active");
                            _.$(this).addClass("active");
                        })
                        
                        _.$("#outcomesTabAdmin").click(function(e){
							_.$("#transfersHistoryAdmin #historyOutcome").display("table");
							_.$("#transfersHistoryAdmin #historyIncome").display("none");
                        })
                        
                        _.$("#incomesTabAdmin").click(function(e){
							_.$("#transfersHistoryAdmin #historyOutcome").display("none");
							_.$("#transfersHistoryAdmin #historyIncome").display("table");
                        })
                       
                    }, function(e){
                        console.error(e)
                    }, function(r){
                        console.error(r)
                    })
                }
            }
        }
        
        ,Deposits:{
            init: function(){
                _.$("#deposits .accept").click(this.onAccept);
                _.$("#deposits .decline").click(this.onDecline);
                A.Actions.Cashout.History.init();
                this.Amount.init()
            }
            
            ,Amount:{
                init: function(){
                    _.$("tr .editAmount").click(this.onStart)
                }
                
                ,onStart: function(){
                    var amo = _.$(this).parent().find("span").HTML()*1;
                    _.$(this).removeClass("editAmount").addClass("saveAmount");
                    _.$(this).unevent("click", A.Actions.Deposits.Amount.onStart);
                    _.$(this).click(A.Actions.Deposits.Amount.onSave);
                    
                    _.$(this).parent().find("span").HTML("<input type='number' value='"+amo+"'/>");
                }
                
                ,onSave: function(){
                    var amo = _.$(this).parent().find("input").val
                    var id = _.$(this).parent(2).data("id");
                    
                    var t = this;
                    t.disabled = true;
                    _.$(t).parent().find("span input")[0].disabled=true;
                    
                    _.postJSON("/admin/modules/depositAmount.php",{amount: amo, id: id}, function(d){
                        
                        t.disabled = false;
                        _.$(t).parent().find("span").HTML(_.$(t).parent().find("span input").val);
                        
                        _.$(t).addClass("editAmount").removeClass("saveAmount").unevent("click", A.Actions.Deposits.Amount.onSave).click(A.Actions.Deposits.Amount.onStart);
                        
                    }, function(e){
                        console.error(e)
                    }, function(r){
                        console.error(r);
                    })
                }
            }
            
            ,onAccept: function(){
				var id = _.$(this).parent(2).data("id");
				var note = $(this).parent().find("#note").val()
				var user = $(this).parent().find("#user_id").val()
				A.Actions.Deposits.change({ id: id, status: 1, note: note, user: user})
            }
            
			, onDecline: function () {
				var id = _.$(this).parent(2).data("id");
				var note = $(this).parent().find("#note").val()
				var user = $(this).parent().find("#user_id").val()
				A.Actions.Deposits.change({ id: id, status: -1, note: note, user: user})
            }
            
            ,change: function(s){
                _.postJSON("/admin/modules/depositStatus.php",s,function(d){
                    _.$("#deposits tr[data-id='"+s.id+"'] .buttons").HTML("");
                    _.$("#deposits tr[data-id='"+s.id+"'] .stat").HTML(d);
                },function(e){
                    console.error(e)
                }, function(r){
                    console.error(r);
                })
            }
        }

        ,Inbox:{
            init: function(){
                //_.$("#newMessage_button").click(this.openNewMessage);
                _.$("#inbox .mark_read").click(this.markAsRead);
                _.$("#inbox .reply").click(this.reply);
                _.$("#inbox #send_everyone").click(this.sendToEveryone);
                _.$("#newMessage_form").submit(this.submitRequest);

                _.$(".nav a.inbox").click(function(e){
                    e.preventDefault();
                    _.$(".nav a").removeClass("active")
                    _.$(this).addClass("active");
                    _.$("#tableInbox").display('table')
                    _.$("#tableOutbox").display('none')

                });
                _.$(".nav a.outbox").click(function(e){
                    e.preventDefault();
                    _.$(".nav a").removeClass("active")
                    _.$(this).addClass("active");
                    _.$("#tableInbox").display('none')
                    _.$("#tableOutbox").display("table");

                });
            }

            ,sendToEveryone: function(e) {
                e.preventDefault();
                A.Actions.Inbox.answerFrom = '';
                A.Actions.Inbox.to_name = '$EVERYONE$';
                _.$("#newMessage h3").HTML('Send a message to everyone');
                _.$("#newMessage").display("block");
            }

            ,reply: function(e) {
                e.preventDefault();
                A.Actions.Inbox.answerFrom = _.$(this).data('id');
                A.Actions.Inbox.to_name = _.$(this).data('playername');
                _.$("#newMessage h3").HTML('Send a message to : ' + A.Actions.Inbox.to_name);
                _.$("#newMessage").display("block");
            }

            ,markAsRead: function(e) {
                var id = _.$(this).data('id');
                _.postJSON("/profile/modules/markMessageRead.php",{
                    message:id
                }, function(d){}, function(d){}, function(d){});
                _.$(this).parent().parent().toggleClass('message-unread');
            }

            ,submitRequest: function(e) {
                e.preventDefault();
                _.$("#newMessage_form p.status").HTML("<i class='info'>Sending message...</i>");
                if (A.Actions.Inbox.to_name !== '$EVERYONE$' && (!A.Actions.Inbox.answerFrom || !A.Actions.Inbox.to_name)) throw 'ERROR';
                _.postJSON("/profile/modules/sendMessage.php",{
                    answer_from: A.Actions.Inbox.answerFrom || '',
                    to_name: A.Actions.Inbox.to_name,
                    message: _.$("#newMessage_form [name='messageContent']").val,
                },function(d){
                    _.$("p.status").HTML("<i class='ok'>"+d+"</i>");
                    location.search = 'q='+new Date().getTime();
                },function(e){
                    _.$("p.status").HTML("<i class='error'>"+e+"</i>");
                },function(r){
                    _.$("p.status").HTML("<i class='error'>An unexpected error occured!</i>");
                    console.error(r)
                });
                return false;
            }
        }

        ,SMTPSettings:{
            init: function () {
                _.$('#smtpSettings input').change(this.onChange);
            }

            ,onChange: function(e){
                var t =  _.$(this);

                var id = t[0].id;
                var value = t.val;

                clearTimeout(A.Actions.Variables.interval);
                A.Actions.Variables.interval = setTimeout(function(){
                    t[0].disabled = true;
                    _.postJSON("/admin/modules/changeVar.php",{variable: id, value: value },function(d){
                        t[0].disabled = false;
                    }, function(e){
                        console.error(e);
                    }, function(r){
                        console.error(r);
                    })
                },1000)


            }
        }
        
        ,TournamentRequests: {
            init: function () {
                $('#filter-tournaments').on('submit', this.onSubmit);
                $('#from-date').datetimepicker({
                    format: 'Y-m-d H:i:s',
                    timepicker: false,
                });
                $('#to-date').datetimepicker({
					format: 'Y-m-d H:i:s',
                    timepicker: false,
                });
                $('#create-from-date').datetimepicker({
					format: 'Y-m-d H:i:s',
                    timepicker: false,
                });
				$('#create-to-date H:i:s').datetimepicker({
                    format: 'Y-m-d',
                    timepicker: false,
                });
            }

            ,onSubmit: function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                console.log(data);
                $.ajax({
					url: '/admin/modules/_tournament_request_table.php',
                    data: data,
                    success: function (res) {
                        console.log(res);
						$('#tournament-request').html(res);
                    },
                    error: function (res) {
						$('#tournament-request').html(res);
                    }
                });
            }
        }

        ,PaymentMethods: {
            init: function () {
                $('#btn-add-payment-method').on('click', function () {
                   _.$('#frmAddPaymentMethod').display('block');
                });
                $('#btn-cancel').on('click', function () {
                   _.$('#frmAddPaymentMethod').display('none');
                });

                $('#frmAddPaymentMethod').on('submit', this.onSubmit);

                $('.btn-delete-payment-method').on('click', this.delete);
            }

            ,delete: function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                _.postJSON("/admin/modules/paymentMethods.php",{
                    action: 'delete',
                    id: id
                }, function (d) {
                    window.location.reload(true);
                });
            }

            ,onSubmit: function (e) {
                e.preventDefault();
                _.postJSON("/admin/modules/paymentMethods.php",{
                    action: 'add',
                    name: $('input[name="name"]').val(),
					description: $('input[name="payment_description"]').val(),
					payment_name: $('input[name="payment_name"]').val(),
					country: $('input[name="country"]').val(),
					payment_address: $('input[name="payment_address"]').val(),
					type: $("input[name='type-payment']:checked").val(),
                },function(d){
                    _.$("p.status").HTML("<i class='ok'>"+d+"</i>");
                    window.location.reload(true);
                },function(e){
                    _.$("p.status").HTML("<i class='error'>"+e+"</i>");
                },function(r){
                    _.$("p.status").HTML("<i class='error'>An unexpected error occured!</i>");
                    console.error(r)
                });
            }
		}
		,ChipTransactions: {
			init: function () {
				$('#chip-transaction').on('submit', this.onSubmit);
				$('#from-date').datetimepicker({
					format: 'Y-m-d H:i:s',
				});
				$('#to-date').datetimepicker({
					format: 'Y-m-d H:i:s',
				});
			}
			, onSubmit: function (e) {
				e.preventDefault();
				_.postJSON("/admin/modules/_chip_transactions.php", {
					fromDate: $('input[name="from_date"]').val(),
					toDate: $('input[name="to_date"]').val(),
					sendName: $('input[name="user"]').val(),
					receivedName: $('input[name="taget"]').val(),
				}, function (d) {
					_.$("p.status").HTML("<i class='ok'>" + d + "</i>");
				}, function (e) {
					_.$("p.status").HTML("<i class='error'>" + e + "</i>");
				}, function (r) {
					_.$("#chipTransactions").HTML(r);
				});
			}
		}
		,TournamentRegistrations: {
			init: function () {
				$('#tournament-registrations').on('submit', this.onSubmit);
				$('#from-date').datetimepicker({
					format: 'Y-m-d H:i:s',
				});
				$('#to-date').datetimepicker({
					format: 'Y-m-d H:i:s',
				});
			}
			, onSubmit: function (e) {
				e.preventDefault();
				_.postJSON("/admin/modules/_tournament_registrations.php", {
					fromDate: $('input[name="from_date"]').val(),
					toDate: $('input[name="to_date"]').val(),
					player: $('input[name="player"]').val(),
					name: $('input[name="name"]').val(),
					creator: $('input[name="creator"]').val(),
					inviteduser: $('input[name="inviteduser"]').val(),
				}, function (d) {
					_.$("p.status").HTML("<i class='ok'>" + d + "</i>");
				}, function (e) {
					_.$("p.status").HTML("<i class='error'>" + e + "</i>");
				}, function (r) {
					_.$("#tournamentRegistrations").HTML(r);
				});
			}
		}
    }
}
_.core(function(){ 
    A.init(); 
    viewTournamentResult = function(id){
        _.$("#tournamentResultView").display("block");
        _.$("#tournamentResultView .wrap .container").HTML("<p class='spinner_big'></p>");
        
        _.$("#tournamentResultView .wrap").get("/profile/modules/tournament-result.php",{tournament_id:id})
    }  
})