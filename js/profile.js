A = {
    init: function(){
        //this.FrameRequest.init();
        this.ChangePassword.init();
        this.Incomes.init();
        this.Transfer.init();
        this.AffiliateRequest.init();
        this.TournamentRequest.init();
        //_.Templates.add("tournamentsTemplate");
        //this.Tournaments.init();
        
        _.$("#details").click(function(e){
            _.$("#details").display("none");
        })
         _.$("#hand-history").click(function(e){
            _.$("#hand-history").display("none");
        })

         _.$("#transfer-history").click(function(e){
            _.$("#transfer-history").display("none");
        })
        _.$("#affiliateRequest").click(function(e){
            if (e.target && e.target.id === 'affiliateRequest') _.$("#affiliateRequest").display("none");
        })
        _.$("#changePassword").click(function(e){
            if (e.target && e.target.id === 'changePassword') _.$("#changePassword").display("none");
        })
        _.$("#details>*").click(function(e){
            e.stopPropagation();
        })

        _.$("#tournamentRequest").click(function(e){
            if (e.target && e.target.id === 'tournamentRequest') _.$("#tournamentRequest").display("none");
        })
    }

    ,ChangePassword:{
        init: function(){
            _.$("#changePassword_button").click(this.openChangePassword);
            _.$("#closeForm").click(function() {_.$("#changePassword").display("none")});
            _.$("#change_password_form").submit(this.submitRequest);
        }

        ,openChangePassword: function() {
            _.$("#changePassword").display("block");
        }

        ,submitRequest: function(e) {
            e.preventDefault();
            _.$("#change_password_form p.status").HTML("<i class='info'>Sending request...</i>");
            _.postJSON("/profile/modules/changePassword.php",{
                password: _.$("#change_password_form [name='password']").val,
                confirmpassword: _.$("#change_password_form [name='confirmpassword']").val,
                token: _.$("#change_password_form [name='token']").val,
            },function(d){
                _.$("#change_password_form p.status").HTML("<i class='ok'>"+d+"</i>");
                location.search = 'q='+new Date().getTime();
            },function(e){
                _.$("#change_password_form p.status").HTML("<i class='error'>"+e+"</i>");
            },function(r){
                _.$("#change_password_form p.status").HTML("<i class='error'>An unexpected error occured!</i>");
                console.error(r)
            });
            return false;
        }
    }

    ,AffiliateRequest:{
        init: function(){
            _.$("#affiliateRequest_button").click(this.openAffiliateRequest);
            _.$("#affiliateRequest_form").submit(this.submitRequest);
        }

        ,openAffiliateRequest: function() {
            _.$("#affiliateRequest").display("block");
        }

        ,submitRequest: function(e) {
            e.preventDefault();
            _.$("#affiliateRequest_form p.status").HTML("<i class='info'>Sending request...</i>");
            _.postJSON("/profile/modules/affiliateRequest.php",{
                company: _.$("#affiliateRequest_form [name='company']").val,
                position: _.$("#affiliateRequest_form [name='position']").val,
                country: _.$("#affiliateRequest_form [name='country']").val,
                hear_about_us: _.$("#affiliateRequest_form [name='hear_about_us']").val,
                how_bring_players: _.$("#affiliateRequest_form [name='how_bring_players']").val,
                how_many_players: _.$("#affiliateRequest_form [name='how_many_players']").val
            },function(d){
                _.$("p.affiliate.status").HTML("<i class='ok'>"+d+"</i>");
                location.search = 'q='+new Date().getTime();
            },function(e){
                _.$("p.affiliate.status").HTML("<i class='error'>"+e+"</i>");
            },function(r){
                _.$("p.affiliate.status").HTML("<i class='error'>An unexpected error occured!</i>");
                console.error(r)
            });
            return false;
        }
        
        ,onClick: function(){
            _.$("p.affiliate.status").HTML("<i class='info'>Sending request...</i>");
            _.postJSON("/profile/modules/affiliateRequest.php",{},function(d){
                 _.$("p.affiliate.status").HTML("<i class='ok'>"+d+"</i>");
            },function(e){
                _.$("p.affiliate.status").HTML("<i class='error'>"+e+"</i>");
            },function(r){
                _.$("p.affiliate.status").HTML("<i class='error'>An unexpected error occured!</i>");
                console.error(r)
            })
        }
    }
    
    ,Incomes: {
        
        init: function(){
            _.$(".referralsLink").click(this.openReferrals)
            this.done = _.$(".referrals .refs tr[data-id]").length;
            this.ids=[];
            //_.$(".referrals .refs tr[data-id]").forEach(this.proceed)
            
            //this.load();
            //_.$("#totalrake").HTML("<i class='spinner_small'></i><span>Calculating..</span>");
            _.$(".referrals .refs .details a").click(A.Incomes.Details.onClick);
            
        }
        
        ,openReferrals: function(e){
            var id = _.$(this).data('id');

            _.$("#details").display("none"); // For back
            _.$("#referral-level2").display("block");
            _.$("#referral-level2 .wrap .container").HTML("<p class='spinner_big'></p>");

             _.$("#referral-level2").click(function(){
                 _.$("#referral-level2").display("none");
             })
            
            _.postJSON("/profile/modules/referrals.php",{id:id},function(d){
               var table = _.new("table");
               table.appendHTML("<tbody></tbody>");

               var displayEmail = d[0] && d[0].email;
               var displayRealName = d[0] && d[0].realname;

               var theadHtml = '<td>Name</td>';
                if (displayEmail) theadHtml += '<td>E-mail</td>';
                if (displayRealName) theadHtml += '<td>Real Name</td>';
                theadHtml += '<td>Tournament Fee</td><td>Hand rake</td><td>Freeroll fee</td><td></td>';
               var thead = _.new("thead").HTML(theadHtml);
               table.appendHTML(thead[0].outerHTML);
               
               tbody = table.find("tbody");
               var sumRake = 0;
                var sumTournaments = 0;
                var sumFreeroll = 0;
               for(var i in d){
                   var line = "<td>"+d[i].name+"</td>";
                   if (displayEmail) line += "<td>"+d[i].email+"</td>";
                   if (displayRealName) line += "<td>"+d[i].realname+"</td>";
                   line += "<td>"+d[i].tournaments_fee+"</td><td>"+d[i].hand_rake+'</td><td>'+d[i].points_dec+'</td><td><a href="#" data-id="'+d[i].id+'" data-from="'+id+'">Details</a></td>';
                   tbody.appendHTML(line);
                   sumTournaments += Number(d[i].tournaments_fee);
                   sumRake += Number(d[i].hand_rake);
                   sumFreeroll += Number(d[i].points_dec);
                  // sum+=d[i].rake.rake*1;
               }

               var colspan = 1;
                if (displayEmail) colspan++;
                if (displayRealName) colspan++;
               tbody.appendHTML("<td colspan='"+colspan+"'>Total : </td><td class='result'>"+sumTournaments+"</td><td class='result'>"+sumRake+"</td><td class='result'>"+sumFreeroll+"</td>");
                _.$("#referral-level2 .wrap .container").HTML(_.$(table)[0].outerHTML);
                _.$("#referral-level2 .wrap .container table a").click(A.Incomes.Details.onClick);
            }, function(m){
                console.error(m);
            }, function(e){
                console.error(e);
            });
            
        }
        
        ,done: 0
        
        ,proceed: function(el){
            var id = el.data("id");
            var field = el.find(".income");
            
            //field.HTML("<i class='spinner_small'></i><span>Calculating..</span>");
            A.Incomes.ids.push(id);
            
        }
        
        ,load:function(){
            // _.postJSON("/profile/modules/calculateRake.php",{id:A.Incomes.ids.join(",")},function(d){
            //     for(var i in d){
            //          _.$(".referrals .refs tr[data-id='"+i+"'] .income").HTML(d[i]);
            //     }
            //     var sum = 0;
            //     for(var i in d){
            //         sum+= parseFloat(_.$(".referrals .refs tr[data-id='"+i+"'] .income .result").HTML());
            //     }
            //     _.$("#totalrake").HTML(Math.round(sum*100)/100);
                
            // },function(d){
            //     field.HTML("<p class='status'><i class='error'>"+d+"</i></p>")
            // },function(e){
            //     field.HTML("<p class='status'><i class='error'>Unexpected error happened while calculations!</i></p>")
            //     console.error(e);
            // })
        }
        
        ,Details: {
            onClick: function(e){
                
                e.preventDefault();
                _.$("#referral-level2").display("none"); // For back
                _.$("#details").display("block");
                _.$("#details .wrap").HTML("<p class='spinner_big'></p>");
                var id = _.$(this).data("id");
                //alert(id);
                _.$("#details .wrap").get("/profile/modules/details.php",{id:id, from:_.$(this).data("from")},A.Incomes.Details.onLoad)
            }
            
            ,onLoad: function(r){
                _.$("#details a.view").click(function(e){
                    e.preventDefault();
                    _.$(this).parent().find("div.hand").display("block");
                })
                
                _.$('#fees').display("none");
                _.$(".nav a.fees").click(function(e){
                    e.preventDefault();
                    _.$(".nav a").removeClass("active")
                    _.$(this).addClass("active");
                    _.$("#details table").display('none')
                    _.$("#fees").display("table");
                     _.$("#rakes").display('none')
                    
                })
                _.$(".nav a.rakes").click(function(e){
                    e.preventDefault();
                    _.$(".nav a").removeClass("active")
                    _.$(this).addClass("active");
                    _.$("#details table").display('none')
                    _.$("#rakes").display("table");
                     _.$("#rakes").display('none')
                    
                })
                _.$("a#from").click(A.Incomes.openReferrals)
            }
            
        }
    }
    
    ,Transfer: {
        init: function(){
            _.Templates.add("player-option");
            _.$("#transferChips").submit(this.onSubmit);
            _.$("#transfer_player").keydown(this.onPrint);
             _.$("#transferAffiliate").submit(this.onTransferAffiliate);
        }
        
        ,onPrint: function(){
            _.postJSON("/profile/modules/getUserNames.php",{text: _.$(this).val},function(d){
                _.$("#players").fromTemplate("player-option",d);
            },function(e){
                console.error(e)
            }, function(r){
                console.error(r)
            });
        }
        
        ,onSubmit: function(e){
            e.preventDefault();
            _.postJSON("/profile/modules/transferChips.php",{player: _.$("#transfer_player").val, amount: _.$("#transfer_amount").val},
            function(d){
                _.$("#transfer-status").HTML("<i class='ok'>Transfer completed successfully! Your balance: "+d+"</i>");
                _.$(".accBalance").HTML(d);
            },function(e){
                _.$("#transfer-status").HTML("<i class='error'>"+e+"</i>");
            },function(r){
                _.$("#transfer-status").HTML("<i class='error'> An unexpected error occured!</i>");
                console.error(r);
            });
        }
        ,onTransferAffiliate: function(e){
            e.preventDefault();
            _.postJSON("/profile/modules/transferBalance.php",{amount: _.$("#affiliate_amount").val},
            function(d){
                _.$("#affiliate-transfer-status").HTML("<i class='ok'>Your request has been sent successfully! Please waiting admin approve</i>");
                location.search = 'q='+new Date().getTime();
            },function(e){
                _.$("#affiliate-transfer-status").HTML("<i class='error'>"+e+"</i>");
            },function(r){
                _.$("#affiliate-transfer-status").HTML("<i class='error'> An unexpected error occured!</i>");
                console.error(r);
            });
        }
    }

    ,TournamentRequest: {
        init: function () {
            _.Templates.add("player-option2");
            _.$('#request_tournament_button').click(this.openTournamentRequest);
            _.$('#tournamentRequest_form').submit(this.submit);
            _.$("#invite_users").keydown(this.onPrint);
            $('input[name="seats"]').on('change', this.calChips);
            $('input[name="tables"]').on('change', this.calChips);
            jQuery('#starttime').datetimepicker({
                format: 'Y-m-d H:i'
            });
        }

        ,calChips: function () {
            console.log('on change');
            var tables = $('input[name="tables"]').val() || 0;
            var seats = $('input[name="seats"]').val() || 0;
            var chipPerSeat = $('#tournament_request_chips_per_seat').val();
            var chipsToPay = tables * seats * chipPerSeat;
            console.log(chipsToPay);
            $('#chips_to_pay').val(chipsToPay);
        }

        ,onPrint: function(){
            _.postJSON("/profile/modules/getUserNames.php",{text: _.$(this).val},function(d){
                _.$("#players2").fromTemplate("player-option2",d);
            },function(e){
                console.error(e)
            }, function(r){
                console.error(r)
            });
        }

        ,openTournamentRequest: function () {
            console.log('clicked');
            _.$("#tournamentRequest").display("block");
        }
        ,submit: function (e) {
            e.preventDefault();
            _.$("#tournamentRequest_form .status").HTML("<i class='info'>Sending request...</i>");
            _.postJSON("/profile/modules/requestTournament.php",{
                'game': _.$('select[name="game"]').val,
                'tables': _.$('input[name="tables"]').val,
                'seats': _.$('input[name="seats"]').val,
                'starttime': _.$('input[name="starttime"]').val,
                'invite_users': _.$('input[name="invite_users"]').val,
                'buyin': _.$('input[name="buyin"]').val,
                'MaxRebuys': _.$('input[name="maxrebuys"]').val,
            },function(d){
                _.$("#tournamentRequest_form .status").HTML("<i class='ok'>"+d+"</i>");
            },function(e){
                _.$("#tournamentRequest_form .status").HTML("<i class='error'>"+e+"</i>");
            },function(r){
                _.$("#tournamentRequest_form .status").HTML("<i class='error'>An unexpected error occured!</i>");
                console.error(r)
            })
        }
    }

}

_.core(function(){
    A.init();  
    pagination = function(id,page){
        _.$("#details .wrap").get("/profile/modules/details.php",{id:id,page:page},A.Incomes.Details.onLoad)
    }  
    openHanHistory = function(handId,date,name){
         _.$("#hand-history").display("block");
        _.$("#hand-history .wrap .container").HTML("<p class='spinner_big'></p>");
        
        _.$("#hand-history .wrap").get("/profile/modules/handHistory.php",{hand_id:handId,date:date,name:name},A.Incomes.Details.onLoad)
    }

    openTransferHistory = function(){
         _.$("#transfer-history").display("block");
        _.$("#transfer-history .wrap .container").HTML("<p class='spinner_big'></p>");
        
		_.$("#transfer-history .wrap").get("/profile/modules/transferBalanceHistory.php", { },A.Incomes.Details.onLoad)
    }
    viewTournamentResult = function(id){
        _.$("#tournamentResultView").display("block");
        _.$("#tournamentResultView .wrap .container").HTML("<p class='spinner_big'></p>");
        
        _.$("#tournamentResultView .wrap").get("/profile/modules/tournament-result.php",{tournament_id:id})

         _.$("#tournamentResultView").click(function(){
            _.$("#tournamentResultView").display("none");
         });
         
    }
})  