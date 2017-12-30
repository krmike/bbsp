/**
 * Created by krmike on 13.05.2015.
 * need tests
 */

$(document).ready(function(){

    $('.pLocation').css("opacity", 0);
    $('.table-responsive').on('show.bs.dropdown', function () {
        $('.table-responsive').css( "min-height", "400px" );
    });

    $('.table-responsive').on('hide.bs.dropdown', function () {
        $('.table-responsive').css( "min-height", "none" );
    })

    $(".delete_user").click(function() {
        if (!confirm("Are you sure you want to delete user?")) {
            return false;
        }
    })
    $(".delete_equip").click(function() {
        if (!confirm("Are you sure you want to delete equipment?")) {
            return false;
        }
    })
    $(".delete_lift").click(function() {
        if (!confirm("Are you sure you want to delete lift?")) {
            return false;
        }
    })
    $(".delete_run").click(function() {
        if (!confirm("Are you sure you want to delete run?")) {
            return false;
        }
    })
    $(".delete_activity").click(function() {
        if (!confirm("Are you sure you want to delete activity?")) {
            return false;
        }
    })
    $(".delete_weather").click(function() {
        if (!confirm("Are you sure you want to delete weather?")) {
            return false;
        }
    })
    $(".delete_snow").click(function() {
        if (!confirm("Are you sure you want to delete snow?")) {
            return false;
        }
    })
    $(".delete_visibility").click(function() {
        if (!confirm("Are you sure you want to delete visibility?")) {
            return false;
        }
    })
    $(".delete_role").click(function() {
        if (!confirm("Are you sure you want to delete role?")) {
            return false;
        }
    })
    $(".delete_location").click(function() {
        if (!confirm("Are you sure you want to delete injury location?")) {
            return false;
        }
    })
    $(".delete_category").click(function() {
        if (!confirm("Are you sure you want to delete injury category?")) {
            return false;
        }
    })
    $(".delete_injury_type").click(function() {
        if (!confirm("Are you sure you want to delete injury type?")) {
            return false;
        }
    })
    $(".delete_transport").click(function() {
        if (!confirm("Are you sure you want to delete transport?")) {
            return false;
        }
    })
    $(".delete_dr").click(function() {
        if (!confirm("Are you sure you want to delete Dr?")) {
            return false;
        }
    })
    $(".delete_outcome").click(function() {
        if (!confirm("Are you sure you want to delete referral outcome?")) {
            return false;
        }
    })
    $(".delete_destination").click(function() {
        if (!confirm("Are you sure you want to delete ambulance destination?")) {
            return false;
        }
    })
    $(".delete_trail").click(function() {
        if (!confirm("Are you sure you want to delete trail?")) {
            return false;
        }
    })
    $(".delete_training_category").click(function() {
        if (!confirm("Are you sure you want to delete training category?")) {
            return false;
        }
    })
    $(".delete_training_type").click(function() {
        if (!confirm("Are you sure you want to delete training type?")) {
            return false;
        }
    })
    $('#training_patrollers').multiselect();

    $('.date').datepicker({
        format: "dd-mm-yyyy",
        disableTouchKeyboard: true,
        keyboardNavigation: false
    });

    $( ".time" ).timepicker({
        'timeFormat': 'H:i',
        'step': 5
    });

    $("#reportMap").on("shown.bs.modal", function () {showMap($('#latmap').val(), $('#lngmap').val());});

    getDays('#date_d', $('#date_d').val(), $('#date_m').val(), $('#date_y').val());
    getDays('#date_to_d', $('#date_to_d').val(), $('#date_to_m').val(), $('#date_to_y').val());

    $('#date_m, #date_y').change(function(){
        dateD = $('#date_d').val();
        dateM = $('#date_m').val();
        dateY = $('#date_y').val();
        getDays('#date_d', dateD, dateM, dateY);
    })

    $('#date_to_m, #date_to_y').change(function(){
        dateD = $('#date_to_d').val();
        dateM = $('#date_to_m').val();
        dateY = $('#date_to_y').val();
        getDays('#date_to_d', dateD, dateM, dateY);
    })

})

var incident = {

    init: function(){

        age = calculateAge();
        $('#age-l').html(age);
        $('#age').val(age);

        $("#lift").change(function () {
            val = $("#lift").val();
            $(".runs").removeAttr("selected");
            $(".runs").hide();
            run = ".runs" + val;
            $(run).show();
        })

        getDays('#dob_d', $('#dob_d').val(), $('#dob_m').val(), $('#dob_y').val());
        getDays('#incident_date_d', $('#incident_date_d').val(), $('#incident_date_m').val(), $('#incident_date_y').val());

        $('#dob_m, #dob_y').change(function(){
            dobD = $('#dob_d').val();
            dobM = $('#dob_m').val();
            dobY = $('#dob_y').val();
            getDays('#dob_d', dobD, dobM, dobY);
        })

        $('#incident_date_m, #incident_date_y').change(function(){
            incidentD = $('#incident_date_d').val();
            incidentM = $('#incident_date_m').val();
            incidentY = $('#incident_date_y').val();
            getDays('#incident_date_d', incidentD, incidentM, incidentY);
        })

        $( "#tabs" ).tabs();

        val = $("#mtbb_employee").val();
        if(val != 1) {
            $("#employee_on_duty").hide();
        }

        act = $("#activity").val();

        $("#rental_source").hide();
        if(act != 1) {
            $("#bindings_release").hide();
        }
        if(act != 2) {
            $("#sb_stance").hide();
        }
        if (act == "1" || act == "2" || act == "4" || act == "7") {
            $("#ability").show();
        } else {
            $("#ability").hide();
        }
        $("#spinal_comment").hide();
        if ($("#spinal_injury").val() == 1) {
            $("#spinal_comment").show();
        }
        $("#loc_comment").hide();
        if ($("#loc").val() == 1) {
            $("#loc_comment").show();
        }
        $("#3ml_time").hide();
        $("#6ml_time").hide();
        $(".entonox").hide();
        if ($("#entonox").val() == 1) {
            $(".entonox").show();
        }
        $(".oxygen").hide();
        if ($("#oxygen").val() == 1) {
            $(".oxygen").show();
        }
        if ($("#referral_outcome").val() != 6){
            $(".ambulance").hide();
        }
        $(".trails").hide();
        $(".trails"+$("#activity").val()).show();

        pentraneval = $("#penthrane").val();
        if (pentraneval == 6 ) {
            $("#3ml_time").show();
            $("#6ml_time").show();
        } else {
            $("#3ml_time").hide();
            $("#6ml_time").hide();
        }
        if (pentraneval == 3 ) {
            $("#3ml_time").show();
            $("#6ml_time").hide();
        }
        if (pentraneval == 0) {
            $("#3ml_time").hide();
            $("#6ml_time").hide();
        }

        $("#activity").change(function(){
            val = $("#activity").val();
            $(".trails").hide();
            if (val == "1" ) {
                $("#bindings_release").show();
            } else {
                $("#bindings_release").hide();
            }
            if (val == "2" ) {
                $("#sb_stance").show();
            } else {
                $("#sb_stance").hide();
            }
            if (val == "1" || val == "2" || val == "4" || val == "7") {
                $("#ability").show();
            } else {
                $("#ability").hide();
            }
            trail = ".trails"+val;
            $(trail).show();
        })

        $("#equipment_source").change(function(){
            val = $("#equipment_source").val();
            if (val == "rental" ) {
                $("#rental_source").show();
            } else {
                $("#rental_source").hide();
            }
        })

        $("#mtbb_employee").change(function(){
            val = $("#mtbb_employee").val();
            if (val == "1" ) {
                $("#employee_on_duty").show();
            } else {
                $("#employee_on_duty").hide();
            }
        })

        $("#spinal_injury").change(function(){
            val = $("#spinal_injury").val();
            if (val == 1 ) {
                $("#spinal_comment").show();
            } else {
                $("#spinal_comment").hide();
            }
        })

        $("#loc").change(function(){
            val = $("#loc").val();
            if (val == 1 ) {
                $("#loc_comment").show();
            } else {
                $("#loc_comment").hide();
            }
        })

        $("#penthrane").change(function(){
            val = $("#penthrane").val();
            if (val == 6 ) {
                $("#3ml_time").show();
                $("#6ml_time").show();
            } else {
                $("#3ml_time").hide();
                $("#6ml_time").hide();
            }
            if (val == 3 ) {
                $("#3ml_time").show();
                $("#6ml_time").hide();
            }
            if (val == 0) {
                $("#3ml_time").hide();
                $("#6ml_time").hide();
            }
        })

        $("#entonox").change(function(){
            val = $("#entonox").val();
            if (val == 1 ) {
                $('.witPad').show();
                $(".entonox").show();
            } else {
                $(".entonox").hide();
                $('.witPad').hide();
            }
        })

        $("#oxygen").change(function(){
            val = $("#oxygen").val();
            if (val == 1 ) {
                $(".oxygen").show();
            } else {
                $(".oxygen").hide();
            }
        })

        $("#referral_outcome").change(function(){
            val = $("#referral_outcome").val();
            if (val == 6 ) {
                $(".ambulance").show();
            } else {
                $(".ambulance").hide();
            }
        })

        $('#equipment_select').multiselect();

        $('#add_patroller').click(function(){
            onePatroller = $('#one_patroller').html();
            child = '<tr id="one_patroller">'+onePatroller +'</tr>';
            $('#patrollers').append(child);
            $('.remove-patroller').click(function(){
                vare = $(this).closest('tr').remove();
            })

            $('.i_patrollers').change(function(){
                patroller = this;
                if ($(patroller).val() == "") {
                    $(patroller).css("border-color", "#ff0000");
                } else {
                    $(patroller).css("border-color", "rgb(34, 34, 34)");
                    $(patroller).css("border", "2px inset");
                }
            });
            $('.i_roles').change(function(){
                role = this;
                if ($(role).val() == "") {
                    $(role).css("border-color", "#ff0000");
                } else {
                    $(role).css("border-color", "rgb(34, 34, 34)");
                    $(role).css("border", "2px inset");
                }
            });
        })

        $('.remove-patroller').click(function(){
            vare = $(this).closest('tr').remove();
        })

        $('#add_vitail').click(function(){
            oneVitail = $('#one_vital').html();
            child = '<div class="incident" id="one_vital">'+oneVitail +'</div>';
            $('#vitails').append(child);
            $('.remove-vital').click(function(){
                vare = $(this).closest('#one_vital').remove();
            })
            $('.v_gcs').blur(function(){
                vit = this;
                if ($(vit).val() == "") {
                    parDiv = $(vit).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                } else {
                    $(lDiv).css("color", "#676a6c");
                }
            });
            $('.v_pupils').blur(function(){
                vit = this;
                if ($(vit).val() == "") {
                    parDiv = $(vit).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                } else {
                    $(lDiv).css("color", "#676a6c");
                }
            });
            $('.v_bp').blur(function(){
                vit = this;
                if ($(vit).val() == "") {
                    parDiv = $(vit).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                } else {
                    $(lDiv).css("color", "#676a6c");
                }
            });
            $('.v_respiration').blur(function(){
                vit = this;
                if ($(vit).val() == "") {
                    parDiv = $(vit).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                } else {
                    $(lDiv).css("color", "#676a6c");
                }
            });
            $('.v_pulse').blur(function(){
                vit = this;
                if ($(vit).val() == "") {
                    parDiv = $(vit).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                } else {
                    $(lDiv).css("color", "#676a6c");
                }
            });
            $('.v_skin').blur(function(){
                vit = this;
                if ($(vit).val() == "") {
                    parDiv = $(vit).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                } else {
                    $(lDiv).css("color", "#676a6c");
                }
            });
            $('.v_temp').blur(function(){
                vit = this;
                if ($(vit).val() == "") {
                    parDiv = $(vit).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                } else {
                    $(lDiv).css("color", "#676a6c");
                }
            });
            $('.v_otwo').blur(function(){
                vit = this;
                if ($(vit).val() == "") {
                    parDiv = $(vit).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                } else {
                    $(lDiv).css("color", "#676a6c");
                }
            });
        })

        $('.remove-vital').click(function(){
            vare = $(this).closest('#one_vital').remove();
        })

        $('#add_phone').click(function(){
            onePhone = $('#one_phone').html();
            child = '<div id="one_phone" class="incident">'+onePhone +'</div>';
            $('#phones').append(child);
            $('.remove-phone').click(function(){
                vare = $(this).closest('.incident').remove();
            })
            $('.i_phone').blur(function(){
                phone = this;
                if ($(phone).val() == "") {
                    parDiv = $(phone).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                } else {
                    $(lDiv).css("color", "#676a6c");
                }
            });
        })

        $('.remove-phone').click(function(){
            vare = $(this).closest('.incident').remove();
        })

        $('#add_evidence').click(function(){
            oneEvidence = $('#one_evidence').html();
            child = '<div class="incident">'+oneEvidence +'</div>';
            $('#evidences').append(child);
        })

        $('#drSignature').signaturePad({
            drawOnly:true,
            validateFields: false
        });

        if ($('#dr').val() == 0) {
            $('#drSignature').hide();
        }

        $('#dr').change(function(){
            if ($(this).val() == 0) {
                $('#drSignature').hide();
            }
            if ($(this).val() != 0) {
                $('#drSignature').show();
            }
        })

        $('#add_injury').click(function(){
            injuresCount = $('#add_injury').attr("count");
            injuresCount = parseInt(injuresCount) + parseInt(1);
            $('#add_injury').attr("count", injuresCount);
            oneInjury = $('#one_injury').html();
            child = '<tr id="one_injury'+injuresCount+'" count="'+injuresCount+'">' + oneInjury + '</tr>';
            $('#injures').append(child);
            $('.remove-injury').click(function(){
                vare = $(this).closest('tr').remove();
            })

            $(".injury_category").change(function () {
                clickedCat = $(this);
                valcat = $(this).val();
                parentTr = $(clickedCat).closest("tr");
                parentTr.find(".types").removeAttr("selected");
                parentTr.find(".types").hide();
                typeс = ".types" + valcat;
                parentTr.find(typeс).show();
                if ($(clickedCat).val() == "") {
                    $(clickedCat).css("border-color", "#ff0000");
                } else {
                    $(clickedCat).css("border-color", "rgb(169, 169, 169)");
                    $(clickedCat).css("border", "1px solid");
                }
            })
            $(".inj_type").change(function () {
                if ($(this).val() == "") {
                    $(this).css("border-color", "#ff0000");
                } else {
                    $(this).css("border-color", "rgb(169, 169, 169)");
                    $(this).css("border", "1px solid");
                }
            })
            $('.select-location').click(function(){
                $('.pLocation').css("opacity", 0);
                window.popupID = $(this).closest("tr").attr("count");
                injury = window.popupID;
                if (injury == 1) {
                    thisInjury = $('#one_injury');
                } else {
                    thisInjury = $('#one_injury'+injury);
                }
                locId = $(thisInjury).find('.location_id').val();
                side = $(thisInjury).find('.location_side').val();
                if (side == 1) {
                    locClass = '.ploc'+locId+"-l";
                }
                if (side == 2) {
                    locClass = '.ploc'+locId+"-r";
                }
                if (side == 0) {
                    locClass = '.ploc'+locId;
                }
                $(locClass).css("opacity", 0.5);
            })
        })

        $(".types").hide();
        typeс = ".types" + $("#category").val();
        $(typeс).show();

        $(".injury_category").change(function () {
            clickedCat = $(this);
            valcat = $(this).val();
            parentTr = $(clickedCat).closest("tr");
            parentTr.find(".types").removeAttr("selected");
            parentTr.find(".types").hide();
            typeс = ".types" + valcat;
            parentTr.find(typeс).show();
            if ($(this).val() == "") {
                $(this).css('border-color', "#ff0000");
                return false;
            } else {
                $(this).css("border-color", "rgb(169, 169, 169)");
                $(this).css("border", "1px solid");
            }
        })
        $(".inj_type").change(function () {
            if ($(this).val() == "") {
                $(this).css('border-color', "#ff0000");
                return false;
            } else {
                $(this).css("border-color", "rgb(169, 169, 169)");
                $(this).css("border", "1px solid");
            }
        })

        $('.remove-injury').click(function(){
            vare = $(this).closest('tr').remove();
        })

        $("#mapModal").on("shown.bs.modal", function () {initialize();});

        $("#locationModal").on("shown.bs.modal", function () {setLocation();});

        $('.select-location').click(function(){
            $('.pLocation').css("opacity", 0);
            window.popupID = $(this).closest("tr").attr("count");
            injury = window.popupID;
            if (injury == 1) {
                thisInjury = $('#one_injury');
            } else {
                thisInjury = $('#one_injury'+injury);
            }
            locId = $(thisInjury).find('.location_id').val();
            side = $(thisInjury).find('.location_side').val();
            if (side == 1) {
                locClass = '.ploc'+locId+"-l";
            }
            if (side == 2) {
                locClass = '.ploc'+locId+"-r";
            }
            if (side == 0) {
                locClass = '.ploc'+locId;
            }
            $(locClass).css("opacity", 0.5);
        })

        $('.witPad').signaturePad({
            drawOnly:true,
            validateFields: false
        });
        $('.sigPad').signaturePad({
            drawOnly:true,
            validateFields: false
        });
        $('#casPad').signaturePad({
            drawOnly:true,
            validateFields: false
        });

        if ($('#unable-to-sign').prop('checked') == true) {
            $('#casPad').hide();
        }

        $('#unable-to-sign').click(function(){
            if ($('#unable-to-sign').prop('checked') == true) {
                $('#casPad').hide();
                $('#casualty').append('<div id="unable-reason">Reason: <textarea name="unable_reason"></textarea></div>');
            }
            if ($('#unable-to-sign').prop('checked') == false) {

                $('#unable-reason').remove();
                $('#casPad').show();
            }
        })

        $('#incident-equipment').multiselect();
        $('#equipment-left').multiselect();

        $('#dob_d, #dob_m, #dob_y, #incident_date_d, #incident_date_m, #incident_date_y').blur(function(){
            age = calculateAge();
            $('#age-l').html(age);
            $('#age').val(age);
        });

        $('#first_name').blur(function(){
            validateIFields('#first_name');
        });
        $('#surname').blur(function(){
            validateIFields('#surname');
        });
        $('#gender').blur(function(){
            validateIFields('#gender');
        });
        $('#street').blur(function(){
            validateIFields('#street');
        });
        $('#city').blur(function(){
            validateIFields('#city');
        });
        $('#state').blur(function(){
            validateIFields('#state');
        });
        $('#country').blur(function(){
            validateIFields('#country');
        });
        $('#postcode').blur(function(){
            validateIFields('#postcode');
        });
        $('#allergies').blur(function(){
            validateIFields('#allergies');
        });
        $('#medications').blur(function(){
            validateIFields('#medications');
        });
        $('#last_meals').blur(function(){
            validateIFields('#last_meals');
        });
        $('#last_bath').blur(function(){
            validateIFields('#last_bath');
        });
        $('#i_history').blur(function(){
            validateIFields('#i_history');
        });
        $('#i_description').blur(function(){
            validateIFields('#i_description');
        });
        $('#helmet_worn').blur(function(){
            validateIFields('#helmet_worn');
        });
        $('#activity').blur(function(){
            validateIFields('#activity');
        });
        $('#i_ability').blur(function(){
            validateIFields('#i_ability');
        });
        $('#equipment_source').blur(function(){
            validateIFields('#equipment_source');
        });
        $('#i_rental_source').blur(function(){
            validateIFields('#i_rental_source');
        });
        $('#weather').blur(function(){
            validateIFields('#weather');
        });
        $('#snow').blur(function(){
            validateIFields('#snow');
        });
        $('#visibility').blur(function(){
            validateIFields('#visibility');
        });
        $('#lift').blur(function(){
            validateIFields('#lift');
        });
        $('#run').blur(function(){
            validateIFields('#run');
        });
        $('#incident_coordinates').blur(function(){
            validateIFields('#incident_coordinates');
        });
        $('#symptoms').blur(function(){
            validateIFields('#symptoms');
        });
        $('#spinal_injury').blur(function(){
            validateIFields('#spinal_injury');
        });
        $('#i_spinal_comment').blur(function(){
            validateIFields('#i_spinal_comment');
        });
        $('#loc').blur(function(){
            validateIFields('#loc');
        });
        $('#i_loc_comment').blur(function(){
            validateIFields('#i_loc_comment');
        });
        $('#transport').blur(function(){
            validateIFields('#transport');
        });
        $('#penthrane').blur(function(){
            validateIFields('#penthrane');
        });
        $('#entonox').blur(function(){
            validateIFields('#entonox');
        });
        $('#witness').blur(function(){
            validateIFields('#witness');
        });
        $('#witnessSec').blur(function(){
            validateIFields('#witnessSec');
        });
        $('#entonox_start_amount').blur(function(){
            validateIFields('#entonox_start_amount');
        });
        $('#entonox_end_amount').blur(function(){
            validateIFields('#entonox_end_amount');
        });
        $('#oxygen').blur(function(){
            validateIFields('#oxygen');
        });
        $('#oxygen_flow_rate').blur(function(){
            validateIFields('#oxygen_flow_rate');
        });
        $('#treatment_provided').blur(function(){
            validateIFields('#treatment_provided');
        });
        $('#recommended_advice').blur(function(){
            validateIFields('#recommended_advice');
        });
        $('#referral_outcome').blur(function(){
            validateIFields('#referral_outcome');
        });
        $('#ambulance_destination').blur(function(){
            validateIFields('#ambulance_destination');
        });
        $('#patroller_signature').blur(function(){
            validateIFields('#patroller_signature');
        });

        //$('.i_phone').blur(function(){
        //    phone = this;
        //    if ($(phone).val() == "") {
        //        parDiv = $(phone).parent('.input');
        //        lDiv = $(parDiv).siblings('.label');
        //        $(lDiv).css("color", "#ff0000");
        //    } else {
        //        $(lDiv).css("color", "#676a6c");
        //    }
        //});

        //$('.v_gcs').blur(function(){
        //    vit = this;
        //    if ($(vit).val() == "") {
        //        parDiv = $(vit).parent('.input');
        //        lDiv = $(parDiv).siblings('.label');
        //        $(lDiv).css("color", "#ff0000");
        //    } else {
        //        $(lDiv).css("color", "#676a6c");
        //    }
        //});
        //$('.v_pupils').blur(function(){
        //    vit = this;
        //    if ($(vit).val() == "") {
        //        parDiv = $(vit).parent('.input');
        //        lDiv = $(parDiv).siblings('.label');
        //        $(lDiv).css("color", "#ff0000");
        //    } else {
        //        $(lDiv).css("color", "#676a6c");
        //    }
        //});
        //$('.v_bp').blur(function(){
        //    vit = this;
        //    if ($(vit).val() == "") {
        //        parDiv = $(vit).parent('.input');
        //        lDiv = $(parDiv).siblings('.label');
        //        $(lDiv).css("color", "#ff0000");
        //    } else {
        //        $(lDiv).css("color", "#676a6c");
        //    }
        //});
        //$('.v_respiration').blur(function(){
        //    vit = this;
        //    if ($(vit).val() == "") {
        //        parDiv = $(vit).parent('.input');
        //        lDiv = $(parDiv).siblings('.label');
        //        $(lDiv).css("color", "#ff0000");
        //    } else {
        //        $(lDiv).css("color", "#676a6c");
        //    }
        //});
        //$('.v_pulse').blur(function(){
        //    vit = this;
        //    if ($(vit).val() == "") {
        //        parDiv = $(vit).parent('.input');
        //        lDiv = $(parDiv).siblings('.label');
        //        $(lDiv).css("color", "#ff0000");
        //    } else {
        //        $(lDiv).css("color", "#676a6c");
        //    }
        //});
        //$('.v_skin').blur(function(){
        //    vit = this;
        //    if ($(vit).val() == "") {
        //        parDiv = $(vit).parent('.input');
        //        lDiv = $(parDiv).siblings('.label');
        //        $(lDiv).css("color", "#ff0000");
        //    } else {
        //        $(lDiv).css("color", "#676a6c");
        //    }
        //});
        //$('.v_temp').blur(function(){
        //    vit = this;
        //    if ($(vit).val() == "") {
        //        parDiv = $(vit).parent('.input');
        //        lDiv = $(parDiv).siblings('.label');
        //        $(lDiv).css("color", "#ff0000");
        //    } else {
        //        $(lDiv).css("color", "#676a6c");
        //    }
        //});
        //$('.v_otwo').blur(function(){
        //    vit = this;
        //    if ($(vit).val() == "") {
        //        parDiv = $(vit).parent('.input');
        //        lDiv = $(parDiv).siblings('.label');
        //        $(lDiv).css("color", "#ff0000");
        //    } else {
        //        $(lDiv).css("color", "#676a6c");
        //    }
        //});
        //
        //$('.i_patrollers').change(function(){
        //    patroller = this;
        //    if ($(this).val() == "") {
        //        $(this).css('border-color', "#ff0000");
        //        return false;
        //    } else {
        //        $(this).css("border-color", "rgb(169, 169, 169)");
        //        $(this).css("border", "1px solid");
        //    }
        //});
        //$('.i_roles').change(function(){
        //    role = this;
        //    if ($(this).val() == "") {
        //        $(this).css('border-color', "#ff0000");
        //        return false;
        //    } else {
        //        $(this).css("border-color", "rgb(169, 169, 169)");
        //        $(this).css("border", "1px solid");
        //    }
        //});

        $('#submit_incident').click(function(){

            if ($('#first_name').val() == "") {
                $('#first_name_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#surname').val() == "") {
                $('#surname_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#gender').val() == "") {
                $('#gender_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#street').val() == "") {
                $('#street_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#city').val() == "") {
                $('#city_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#state').val() == "") {
                $('#state_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#country').val() == "") {
                $('#country_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#postcode').val() == "") {
                $('#postcode_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }

            var phoneMistake = "";
            $('.i_phone').each(function(){
                if ($(this).val() == "") {
                    inp = $(this);
                    parDiv = $(inp).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                    $('#ui-id-1').click();
                    phoneMistake = "yes";
                    return false;
                } else {
                    phoneMistake = "nope";
                }
            });
            if (phoneMistake == "yes") {
                return false;
            }

            if ($('#allergies').val() == "") {
                $('#allergies_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#medications').val() == "") {
                $('#medications_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#last_meals').val() == "") {
                $('#last_meals_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#last_bath').val() == "") {
                $('#last_bath_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#i_history').val() == "") {
                $('#i_history_l').css("color", "#ff0000");
                $('#ui-id-1').click();
                return false;
            }
            if ($('#i_description').val() == "") {
                $('#i_description_l').css("color", "#ff0000");
                $('#ui-id-2').click();
                return false;
            }
            if ($('#helmet_worn').val() == "") {
                $('#helmet_worn_l').css("color", "#ff0000");
                $('#ui-id-2').click();
                return false;
            }
            if ($('#activity').val() == "") {
                $('#activity_l').css("color", "#ff0000");
                $('#ui-id-2').click();
                return false;
            }
            if ($('#activity').val() == "1" || $('#activity').val() == "2" || $('#activity').val() == "4" || $('#activity').val() == "7"){
                if ($('#i_ability').val() == "") {
                    $('#i_ability_l').css("color", "#ff0000");
                    $('#ui-id-2').click();
                    return false;
                }
            }
            if ($('#equipment_source').val() == "") {
                $('#equipment_source_l').css("color", "#ff0000");
                $('#ui-id-2').click();
                return false;
            }
            if ($('#equipment_source').val() == "rental") {
                if ($('#i_rental_source').val() == "") {
                    $('#i_rental_source_l').css("color", "#ff0000");
                    $('#ui-id-2').click();
                    return false;
                }
            }
            if ($('#weather').val() == "") {
                $('#weather_l').css("color", "#ff0000");
                $('#ui-id-2').click();
                return false;
            }
            if ($('#snow').val() == "") {
                $('#snow_l').css("color", "#ff0000");
                $('#ui-id-2').click();
                return false;
            }
            if ($('#visibility').val() == "") {
                $('#visibility_l').css("color", "#ff0000");
                $('#ui-id-2').click();
                return false;
            }
            if ($('#lift').val() == "") {
                $('#lift_l').css("color", "#ff0000");
                $('#ui-id-2').click();
                return false;
            }
            if ($('#run').val() == "") {
                $('#run_l').css("color", "#ff0000");
                $('#ui-id-2').click();
                return false;
            }
            if ($('#incident_coordinates').val() == "") {
                $('#incident_coordinates_l').css("color", "#ff0000");
                $('#ui-id-2').click();
                return false;
            }

            var patrollerMistake = "";
            $('.i_patrollers').each(function(){
                if ($(this).val() == "") {
                    $(this).css('border-color', "#ff0000");
                    patrollerMistake = "yes";
                    return false;
                } else {
                    patrollerMistake = "nope";
                }
            })
            if (patrollerMistake == "yes") {
                return false;
            }

            var roleMistake = "";
            $('.i_roles').each(function(){
                if ($(this).val() == "") {
                    $(this).css('border-color', "#ff0000");
                    roleMistake = "yes";
                    return false;
                } else {
                    roleMistake = "nope";
                }
            })
            if (roleMistake == "yes") {
                return false;
            }

            if ($('#symptoms').val() == "") {
                $('#symptoms_l').css("color", "#ff0000");
                $('#ui-id-3').click();
                return false;
            }
            if ($('#spinal_injury').val() == "") {
                $('#spinal_injury_l').css("color", "#ff0000");
                $('#ui-id-3').click();
                return false;
            }
            if ($('#spinal_injury').val() == "1") {
                if ($('#i_spinal_comment').val() == "") {
                    $('#i_spinal_comment_l').css("color", "#ff0000");
                    $('#ui-id-3').click();
                    return false;
                }
            }
            if ($('#loc').val() == "") {
                $('#loc_l').css("color", "#ff0000");
                $('#ui-id-3').click();
                return false;
            }
            if ($('#loc').val() == "1") {
                if ($('#i_loc_comment').val() == "") {
                    $('#i_loc_comment_l').css("color", "#ff0000");
                    $('#ui-id-3').click();
                    return false;
                }
            }

            var locMistake = "";
            $('.location_id').each(function(){
                if ($(this).val() == "") {
                    $(this).css('border-color', "#ff0000");
                    locMistake = "yes";
                    $(this).siblings('.select-location').css('color', "#ff0000");
                    return false;
                } else {
                    locMistake = "nope";
                    $(this).siblings('.select-location').css('color', "#577dd0");
                }
            })
            if (locMistake == "yes") {
                return false;
            }

            var catMistake = "";
            $('.injury_category').each(function(){
                if ($(this).val() == "") {
                    $(this).css('border-color', "#ff0000");
                    catMistake = "yes";
                    return false;
                } else {
                    catMistake = "nope";
                }
            })
            if (catMistake == "yes") {
                return false;
            }

            var typeMistake = "";
            $('.inj_type').each(function(){
                if ($(this).val() == "") {
                    $(this).css('border-color', "#ff0000");
                    typeMistake = "yes";
                    return false;
                } else {
                    typeMistake = "nope";
                }
            })
            if (typeMistake == "yes") {
                return false;
            }

            var vGcsMistake = "";
            $('.v_gcs').each(function(){
                if ($(this).val() == "") {
                    inp = $(this);
                    parDiv = $(inp).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                    $('#ui-id-3').click();
                    vGcsMistake = "yes";
                    return false;
                } else {
                    $(lDiv).css('color', "#577dd0");
                    vGcsMistake = "nope";
                }
            });
            if (vGcsMistake == "yes") {
                return false;
            }

            var vPupilsMistake = "";
            $('.v_pupils').each(function(){
                if ($(this).val() == "") {
                    inp = $(this);
                    parDiv = $(inp).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                    $('#ui-id-3').click();
                    vPupilsMistake = "yes";
                    return false;
                } else {
                    $(lDiv).css('color', "#577dd0");
                    vPupilsMistake = "nope";
                }
            });
            if (vPupilsMistake == "yes") {
                return false;
            }

            var vBpMistake = "";
            $('.v_bp').each(function(){
                if ($(this).val() == "") {
                    inp = $(this);
                    parDiv = $(inp).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                    $('#ui-id-3').click();
                    vBpMistake = "yes";
                    return false;
                } else {
                    $(lDiv).css('color', "#577dd0");
                    vBpMistake = "nope";
                }
            });
            if (vBpMistake == "yes") {
                return false;
            }

            var vRespMistake = "";
            $('.v_respiration').each(function(){
                if ($(this).val() == "") {
                    inp = $(this);
                    parDiv = $(inp).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                    $('#ui-id-3').click();
                    vRespMistake = "yes";
                    return false;
                } else {
                    $(lDiv).css('color', "#577dd0");
                    vRespMistake = "nope";
                }
            });
            if (vRespMistake == "yes") {
                return false;
            }

            var vPulseMistake = "";
            $('.v_pulse').each(function(){
                if ($(this).val() == "") {
                    inp = $(this);
                    parDiv = $(inp).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                    $('#ui-id-3').click();
                    vPulseMistake = "yes";
                    return false;
                } else {
                    $(lDiv).css('color', "#577dd0");
                    vPulseMistake = "nope";
                }
            });
            if (vPulseMistake == "yes") {
                return false;
            }

            var vSkinMistake = "";
            $('.v_skin').each(function(){
                if ($(this).val() == "") {
                    inp = $(this);
                    parDiv = $(inp).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                    $('#ui-id-3').click();
                    vSkinMistake = "yes";
                    return false;
                } else {
                    $(lDiv).css('color', "#577dd0");
                    vSkinMistake = "nope";
                }
            });
            if (vSkinMistake == "yes") {
                return false;
            }

            var vTempMistake = "";
            $('.v_temp').each(function(){
                if ($(this).val() == "") {
                    inp = $(this);
                    parDiv = $(inp).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                    $('#ui-id-3').click();
                    vTempMistake = "yes";
                    return false;
                } else {
                    $(lDiv).css('color', "#577dd0");
                    vTempMistake = "nope";
                }
            });
            if (vTempMistake == "yes") {
                return false;
            }

            var vOtwoMistake = "";
            $('.v_otwo').each(function(){
                if ($(this).val() == "") {
                    inp = $(this);
                    parDiv = $(inp).parent('.input');
                    lDiv = $(parDiv).siblings('.label');
                    $(lDiv).css("color", "#ff0000");
                    $('#ui-id-3').click();
                    vOtwoMistake = "yes";
                    return false;
                } else {
                    $(lDiv).css('color', "#577dd0");
                    vOtwoMistake = "nope";
                }
            });
            if (vOtwoMistake == "yes") {
                return false;
            }

            if ($('#transport').val() == "") {
                $('#transport_l').css("color", "#ff0000");
                $('#ui-id-4').click();
                return false;
            }
            if ($('#penthrane').val() == "") {
                $('#penthrane_l').css("color", "#ff0000");
                $('#ui-id-4').click();
                return false;
            }
            if ($('#entonox').val() == "") {
                $('#entonox_l').css("color", "#ff0000");
                $('#ui-id-4').click();
                return false;
            }
            if ($('#entonox').val() == "1") {
                if ($('#witness').val() == "") {
                    $('#witness_l').css("color", "#ff0000");
                    $('#ui-id-4').click();
                    return false;
                }
                if ($('#witnessSec').val() == "") {
                    $('#witnessSec_l').css("color", "#ff0000");
                    $('#ui-id-4').click();
                    return false;
                }
                if ($('#entonox_start_amount').val() == "") {
                    $('#entonox_start_amount_l').css("color", "#ff0000");
                    $('#ui-id-4').click();
                    return false;
                }
                if ($('#entonox_end_amount').val() == "") {
                    $('#entonox_end_amount_l').css("color", "#ff0000");
                    $('#ui-id-4').click();
                    return false;
                }
            }
            if ($('#oxygen').val() == "") {
                $('#oxygen_l').css("color", "#ff0000");
                $('#ui-id-4').click();
                return false;
            }
            if ($('#oxygen').val() == "1") {
                if ($('#oxygen_flow_rate').val() == "") {
                    $('#oxygen_flow_rate_l').css("color", "#ff0000");
                    $('#ui-id-4').click();
                    return false;
                }
            }
            if ($('#treatment_provided').val() == "") {
                $('#treatment_provided_l').css("color", "#ff0000");
                $('#ui-id-4').click();
                return false;
            }
            if ($('#recommended_advice').val() == "") {
                $('#recommended_advice_l').css("color", "#ff0000");
                $('#ui-id-4').click();
                return false;
            }
            if ($('#referral_outcome').val() == "") {
                $('#referral_outcome_l').css("color", "#ff0000");
                $('#ui-id-4').click();
                return false;
            }
            if ($('#referral_outcome').val() == "6") {
                if ($('#ambulance_destination').val() == "") {
                    $('#ambulance_destination_l').css("color", "#ff0000");
                    $('#ui-id-4').click();
                    return false;
                }
            }
            if ($('#patroller_signature').val() == "") {
                $('#patroller_signature_l').css("color", "#ff0000");
                $('#ui-id-4').click();
                return false;
            }

            if ($('#psInput').val() == "") {
                $('#patroller_signature_l').css("color", "#ff0000");
                $('#ui-id-4').click();
                return false;
            }
            if ($('#dr').val() != "") {
                if ($('#drsInput').val() == "") {
                    $('#dr_l').css("color", "#ff0000");
                    $('#ui-id-4').click();
                    return false;
                }
            }

            if ($('#unable-to-sign').prop('checked') == false) {
                if ($('#csInput').val() == "") {
                    $('#casualty_signature_l').css("color", "#ff0000");
                    $('#ui-id-4').click();
                    return false;
                }
            }

            $('#submitted').val("1");
        })
    }

}

function validatePhones() {
    phone = this;
    if ($(phone).val() == "") {
        $(phone).css("border-color", "#ff0000");
        return false;
    }
}

function validateIFields(fieldName) {
    if ($(fieldName).val() != "") {
        $(fieldName+"_l").css("color", "#676a6c");
        return false;
    }
    if ($(fieldName).val() == "") {
        $(fieldName+"_l").css("color", "#ff0000");
        return false;
    }
}

function getDays(dayInput, day, month, year) {
    $.post("/get_days.php", { day: day, month: month, year: year},
        function(days){
            $(dayInput).html(days);
        }, "html");
}

function calculateAge() {
    dobD = $('#dob_d').val();
    dobM = $('#dob_m').val();
    dobY = $('#dob_y').val();
    iDateD = $('#incident_date_d').val();
    iDateM = $('#incident_date_m').val();
    iDateY = $('#incident_date_y').val();
    age = iDateY - dobY;
    if (dobM > iDateM) {
        age = age-1;
    }
    if (dobM = iDateM && dobD > iDateD) {
        age = age-1;
    }
    return age;
}

var settings = {
    addUser: function() {
        $('#login').blur(function(){
            login = $('#login').val();
            if (login != "") {
                checkExistingLogin(login);
            };
        });
    }
}

function checkExistingLogin(login) {
    $.post("/settings/check_login.php", { login: login},
        function(errorText){
            if (errorText == "count") {
                $('#login-label').html("Login already exists!");
                $('#add-user').attr("disabled", "disabled");
            }
            if (errorText == "no") {
                $('#login-label').html("");
                $('#add-user').removeAttr("disabled");
            }
        });
}

var dailyRun = {
    init: function() {

        $('#timeHours').change(function(){
            openingVal = $('#openingReport').prop('checked');
            if (openingVal == true) {
                $(this).val("09");
            }
        })

        $('#timeMinutes').change(function(){
            openingVal = $('#openingReport').prop('checked');
            if (openingVal == true) {
                $(this).val("00");
            }
        })

        $('#openingReport').change(function(){
            openingVal = $('#openingReport').prop('checked');
            if (openingVal == true) {
                $('#timeHours').val("09");
                $('#timeMinutes').val("00");
            }
        })

        $(".lift").change(function () {
            val = $(this).val();
            liftTr = $(this).parent('.liftTd');
            runTr = $(liftTr).siblings('.runTd');
            $(runTr).find(".runs").removeAttr("selected");
            $(runTr).find(".runs").hide();
            run = ".runs" + val;
            $(runTr).find(run).show();
        })
        $('.new-run').hide();
        $('#submit').hide();
        $('#add-run').click(function(){
            $('.new-run').show();
            $('#add-run').remove();
            $('#submit').show();
        });

        $('.duplicate-run').click(function(){
            myrun = $(this).closest('tr');
            lift = myrun.find('.lift-id').html();
            run = myrun.find('.run-id').html();
            status = myrun.find('.status-id').html();
            poles = myrun.find('.poles').html();
            signs = myrun.find('.signs').html();
            fences = myrun.find('.fences').html();
            cones = myrun.find('.cones').html();
            comment = myrun.find('.comment').html();
            $('.run').each(function(){
                if ($(this).val() == run) {
                    liftTr = $(this).parents('.new-run');
                    $(liftTr).find('.poles').val(poles);
                    $(liftTr).find('.signs').val(signs);
                    $(liftTr).find('.fences').val(fences);
                    $(liftTr).find('.cones').val(cones);
                    $(liftTr).find('.comment').val(comment);
                }
            })

        })
    }
}

var dailyLog = {
    init: function(){
        $(".type").hide();
        typeс = ".type" + $("#category").val();
        $(typeс).show();

        $("#category").change(function () {
            val = $("#category").val();
            $(".type").removeAttr("selected");
            $(".type").hide();
            typeс = ".type" + val;
            $(typeс).show();
        })
        $('#daily_log_incidents').multiselect();
        $('.new-log').hide();
        $('#submit').hide();
        $('#add-log').click(function(){
            $('.new-log').show();
            $('#add-log').remove();
            $('#submit').show();
        })
        $('.comment').hide();
        $('.patroller').click(function(){
            id = $(this).val();
            $('#comment'+id).toggle();
        })
    }
}

function setLocation() {
    injury = window.popupID;
    if (injury == 1) {
        thisInjury = $('#one_injury');
    } else {
        thisInjury = $('#one_injury'+injury);
    }
    $('.location_head').click(function(){
        $(thisInjury).find('.location_id').val(1);
        $(thisInjury).find('.location_side').val(0);
        $(thisInjury).find('.location_name').html("Head<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc1').css("opacity", 0.5);
    })
    $('.location_face').click(function(){
        $(thisInjury).find('.location_id').val(2);
        $(thisInjury).find('.location_side').val(0);
        $(thisInjury).find('.location_name').html("Face<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc2').css("opacity", 0.5);
    })
    $('.location_neck').click(function(){
        $(thisInjury).find('.location_id').val(3);
        $(thisInjury).find('.location_side').val(0);
        $(thisInjury).find('.location_name').html("Neck<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc3').css("opacity", 0.5);
    })
    $('.location_spine').click(function(){
        $(thisInjury).find('.location_id').val(4);
        $(thisInjury).find('.location_side').val(0);
        $(thisInjury).find('.location_name').html("Spine<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc4').css("opacity", 0.5);
    })
    $('.location_back-l').click(function(){
        $(thisInjury).find('.location_id').val(5);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Back L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc5-l').css("opacity", 0.5);
    })
    $('.location_back-r').click(function(){
        $(thisInjury).find('.location_id').val(5);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Back R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc5-r').css("opacity", 0.5);
    })
    $('.location_thorax').click(function(){
        $(thisInjury).find('.location_id').val(6);
        $(thisInjury).find('.location_side').val(0);
        $(thisInjury).find('.location_name').html("Thorax<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc6').css("opacity", 0.5);
    })
    $('.location_abdomen').click(function(){
        $(thisInjury).find('.location_id').val(7);
        $(thisInjury).find('.location_side').val(0);
        $(thisInjury).find('.location_name').html("Abdomen<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc7').css("opacity", 0.5);
    })
    $('.location_pelvis').click(function(){
        $(thisInjury).find('.location_id').val(8);
        $(thisInjury).find('.location_side').val(0);
        $(thisInjury).find('.location_name').html("Pelvis<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc8').css("opacity", 0.5);
    })
    $('.location_shoulder-l').click(function(){
        $(thisInjury).find('.location_id').val(9);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Shoulder L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc9-l').css("opacity", 0.5);
    })
    $('.location_shoulder-r').click(function(){
        $(thisInjury).find('.location_id').val(9);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Shoulder R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc9-r').css("opacity", 0.5);
    })
    $('.location_upperarm-l').click(function(){
        $(thisInjury).find('.location_id').val(10);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Upper Arm L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc10-l').css("opacity", 0.5);
    })
    $('.location_upperarm-r').click(function(){
        $(thisInjury).find('.location_id').val(10);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Upper Arm R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc10-r').css("opacity", 0.5);
    })
    $('.location_elbow-l').click(function(){
        $(thisInjury).find('.location_id').val(11);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Elbow L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc11-l').css("opacity", 0.5);
    })
    $('.location_elbow-r').click(function(){
        $(thisInjury).find('.location_id').val(11);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Elbow R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc11-r').css("opacity", 0.5);
    })
    $('.location_forearm-l').click(function(){
        $(thisInjury).find('.location_id').val(12);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Lower Arm L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc12-l').css("opacity", 0.5);
    })
    $('.location_forearm-r').click(function(){
        $(thisInjury).find('.location_id').val(12);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Lower Arm R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc12-r').css("opacity", 0.5);
    })
    $('.location_wrist-l').click(function(){
        $(thisInjury).find('.location_id').val(13);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Wrist L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc13-l').css("opacity", 0.5);
    })
    $('.location_wrist-r').click(function(){
        $(thisInjury).find('.location_id').val(13);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Wrist R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc13-r').css("opacity", 0.5);
    })
    $('.location_hand-l').click(function(){
        $(thisInjury).find('.location_id').val(14);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Hand (including fingers) L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc14-l').css("opacity", 0.5);
    })
    $('.location_hand-r').click(function(){
        $(thisInjury).find('.location_id').val(14);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Hand (including fingers) R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc14-r').css("opacity", 0.5);
    })
    $('.location_thigh-l').click(function(){
        $(thisInjury).find('.location_id').val(15);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Upper Leg L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc15-l').css("opacity", 0.5);
    })
    $('.location_thigh-r').click(function(){
        $(thisInjury).find('.location_id').val(15);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Upper Leg R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc15-r').css("opacity", 0.5);
    })
    $('.location_knee-l').click(function(){
        $(thisInjury).find('.location_id').val(16);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Knee L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc16-l').css("opacity", 0.5);
    })
    $('.location_knee-r').click(function(){
        $(thisInjury).find('.location_id').val(16);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Knee R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc16-r').css("opacity", 0.5);
    })
    $('.location_lowerleg-l').click(function(){
        $(thisInjury).find('.location_id').val(17);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Lower Leg L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc17-l').css("opacity", 0.5);
    })
    $('.location_lowerleg-r').click(function(){
        $(thisInjury).find('.location_id').val(17);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Lower Leg R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc17-r').css("opacity", 0.5);
    })
    $('.location_ankle-l').click(function(){
        $(thisInjury).find('.location_id').val(18);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Ankle L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc18-l').css("opacity", 0.5);
    })
    $('.location_ankle-r').click(function(){
        $(thisInjury).find('.location_id').val(18);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Ankle R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc18-r').css("opacity", 0.5);
    })
    $('.location_foot-l').click(function(){
        $(thisInjury).find('.location_id').val(19);
        $(thisInjury).find('.location_side').val(1);
        $(thisInjury).find('.location_name').html("Foot (including toes) L<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc19-l').css("opacity", 0.5);
    })
    $('.location_foot-r').click(function(){
        $(thisInjury).find('.location_id').val(19);
        $(thisInjury).find('.location_side').val(2);
        $(thisInjury).find('.location_name').html("Foot (including toes) R<br>");
        $('.pLocation').css("opacity", 0);
        $('.ploc19-r').css("opacity", 0.5);
    })
}

function initialize() {

    var centerLatLng = new google.maps.LatLng(-37.8412710051324, 146.26747459173203);
    var mapOptions = {
        zoom: 16,
        center: centerLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById("GMapContainer"), mapOptions);

    var infoWindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        position: map.getCenter(),
        map: map,
        title: 'Click to zoom'
    });

    var malteseCrossTBar = [
        new google.maps.LatLng(-37.83951579285568,  146.2657622695133),
        new google.maps.LatLng(-37.838049965550866, 146.26966756583897)
    ];
    var flightPath = new google.maps.Polyline({
        path: malteseCrossTBar,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var hutRunPlatter = [
        new google.maps.LatLng(-37.84159865096039, 146.2677597105585),
        new google.maps.LatLng(-37.83821374277653, 146.27034804221694)
    ];
    var flightPath = new google.maps.Polyline({
        path: hutRunPlatter,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var summitTBar = [
        new google.maps.LatLng(-37.84160500546046, 146.26830956336562),
        new google.maps.LatLng(-37.838374730663936, 146.27375444766585)
    ];
    var flightPath = new google.maps.Polyline({
        path: summitTBar,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var paintedTBar = [
        new google.maps.LatLng(-37.84063064238961, 146.27213439346178),
        new google.maps.LatLng(-37.83868187763233, 146.27472004295214)
    ];
    var flightPath = new google.maps.Polyline({
        path: paintedTBar,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var bigHillPoma = [
        new google.maps.LatLng(-37.8418147036555, 146.2682988345705),
        new google.maps.LatLng(-37.84144335387129, 146.27089065314976)
    ];
    var flightPath = new google.maps.Polyline({
        path: bigHillPoma,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var bigHillPlatter = [
        new google.maps.LatLng(-37.84287098746978, 146.26946371795384),
        new google.maps.LatLng(-37.841756842548584, 146.27092283965794)
    ];
    var flightPath = new google.maps.Polyline({
        path: bigHillPlatter,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var tankHillPlatter = [
        new google.maps.LatLng(-37.8418803666041, 146.2676416933209),
        new google.maps.LatLng(-37.84200321970577, 146.26622548696105)
    ];
    var flightPath = new google.maps.Polyline({
        path: tankHillPlatter,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var magicCarpet = [
        new google.maps.LatLng(-37.84204558279682, 146.26775166389052),
        new google.maps.LatLng(-37.84220444417156, 146.2677945792757)
    ];
    var flightPath = new google.maps.Polyline({
        path: magicCarpet,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    google.maps.event.addListener(map, 'click', function(event) {
        deleteMarker();
        addMarker(event.latLng);
    });

    function addMarker(location) {
        marker = new google.maps.Marker({
            position: location,
            map: map
        });
        //coordinates = marker.position['A']+", "+marker.position['F'];
        var mapLat = marker.getPosition().lat();
        var mapLng = marker.getPosition().lng();
        var coordinates = mapLat+', '+mapLng;
        $('#incident_coordinates').val(coordinates);
    }

    function deleteMarker() {
        marker.setMap(null);
        marker = "";
    }


}

function showMap( lat, lng) {

    var centerLatLng = new google.maps.LatLng(lat, lng);
    var mapOptions = {
        zoom: 16,
        center: centerLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById("reportMapContainer"), mapOptions);

    var infoWindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        position: map.getCenter(),
        map: map,
        title: 'Click to zoom'
    });

    var malteseCrossTBar = [
        new google.maps.LatLng(-37.83951579285568,  146.2657622695133),
        new google.maps.LatLng(-37.838049965550866, 146.26966756583897)
    ];
    var flightPath = new google.maps.Polyline({
        path: malteseCrossTBar,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var hutRunPlatter = [
        new google.maps.LatLng(-37.84159865096039, 146.2677597105585),
        new google.maps.LatLng(-37.83821374277653, 146.27034804221694)
    ];
    var flightPath = new google.maps.Polyline({
        path: hutRunPlatter,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var summitTBar = [
        new google.maps.LatLng(-37.84160500546046, 146.26830956336562),
        new google.maps.LatLng(-37.838374730663936, 146.27375444766585)
    ];
    var flightPath = new google.maps.Polyline({
        path: summitTBar,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var paintedTBar = [
        new google.maps.LatLng(-37.84063064238961, 146.27213439346178),
        new google.maps.LatLng(-37.83868187763233, 146.27472004295214)
    ];
    var flightPath = new google.maps.Polyline({
        path: paintedTBar,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var bigHillPoma = [
        new google.maps.LatLng(-37.8418147036555, 146.2682988345705),
        new google.maps.LatLng(-37.84144335387129, 146.27089065314976)
    ];
    var flightPath = new google.maps.Polyline({
        path: bigHillPoma,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var bigHillPlatter = [
        new google.maps.LatLng(-37.84287098746978, 146.26946371795384),
        new google.maps.LatLng(-37.841756842548584, 146.27092283965794)
    ];
    var flightPath = new google.maps.Polyline({
        path: bigHillPlatter,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var tankHillPlatter = [
        new google.maps.LatLng(-37.8418803666041, 146.2676416933209),
        new google.maps.LatLng(-37.84200321970577, 146.26622548696105)
    ];
    var flightPath = new google.maps.Polyline({
        path: tankHillPlatter,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var magicCarpet = [
        new google.maps.LatLng(-37.84204558279682, 146.26775166389052),
        new google.maps.LatLng(-37.84220444417156, 146.2677945792757)
    ];
    var flightPath = new google.maps.Polyline({
        path: magicCarpet,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

}



function showReportMap(coords) {

    var centerLatLng = new google.maps.LatLng(-37.8406052,146.2681755);
    var mapOptions = {
        zoom: 16,
        center: centerLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById("reportMapContainer"), mapOptions);

    for( i=0, l = coords.length; i<l; i++) {
        var ltlng = coords[i].split(",");
        var mapCenter = new google.maps.LatLng(ltlng[0], ltlng[1]);
        var marker = new google.maps.Marker({
            position: mapCenter,
            map: map
        });
        marker.setMap(map);
    };

    var infoWindow = new google.maps.InfoWindow();

    function addReportMarker(location) {
        marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }

    var malteseCrossTBar = [
        new google.maps.LatLng(-37.83951579285568,  146.2657622695133),
        new google.maps.LatLng(-37.838049965550866, 146.26966756583897)
    ];
    var flightPath = new google.maps.Polyline({
        path: malteseCrossTBar,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var hutRunPlatter = [
        new google.maps.LatLng(-37.84159865096039, 146.2677597105585),
        new google.maps.LatLng(-37.83821374277653, 146.27034804221694)
    ];
    var flightPath = new google.maps.Polyline({
        path: hutRunPlatter,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var summitTBar = [
        new google.maps.LatLng(-37.84160500546046, 146.26830956336562),
        new google.maps.LatLng(-37.838374730663936, 146.27375444766585)
    ];
    var flightPath = new google.maps.Polyline({
        path: summitTBar,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var paintedTBar = [
        new google.maps.LatLng(-37.84063064238961, 146.27213439346178),
        new google.maps.LatLng(-37.83868187763233, 146.27472004295214)
    ];
    var flightPath = new google.maps.Polyline({
        path: paintedTBar,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var bigHillPoma = [
        new google.maps.LatLng(-37.8418147036555, 146.2682988345705),
        new google.maps.LatLng(-37.84144335387129, 146.27089065314976)
    ];
    var flightPath = new google.maps.Polyline({
        path: bigHillPoma,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var bigHillPlatter = [
        new google.maps.LatLng(-37.84287098746978, 146.26946371795384),
        new google.maps.LatLng(-37.841756842548584, 146.27092283965794)
    ];
    var flightPath = new google.maps.Polyline({
        path: bigHillPlatter,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var tankHillPlatter = [
        new google.maps.LatLng(-37.8418803666041, 146.2676416933209),
        new google.maps.LatLng(-37.84200321970577, 146.26622548696105)
    ];
    var flightPath = new google.maps.Polyline({
        path: tankHillPlatter,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);

    var magicCarpet = [
        new google.maps.LatLng(-37.84204558279682, 146.26775166389052),
        new google.maps.LatLng(-37.84220444417156, 146.2677945792757)
    ];
    var flightPath = new google.maps.Polyline({
        path: magicCarpet,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    flightPath.setMap(map);


}
