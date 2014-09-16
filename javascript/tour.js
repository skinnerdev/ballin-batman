$(function() {
    var $demo, duration, remaining, tour;
    //$demo = $("#demo");
    duration = 5000;
    remaining = duration;
    tour = new Tour({
        onStart: function() {
            //return $demo.addClass("disabled", true);
        },
        onEnd: function() {
            //return $demo.removeClass("disabled", true);
        },
        debug: false,
        steps: [
        {
            // 0
            // NEW PROJECT PAGE
            path: "/new_project.php",
            orphan: true,
            title: "Welcome to the Factionizer!",
            content: "This tour will give you a quick overview of Factionizer.",
            backdrop: true,
        }, {
            // 1
            path: "/new_project.php",
            element: "#project-name",
            placement: "top",
            title: "Create a project",
            content: "Here you can enter a name for your project. We've given it a name for now. You can change the name now if you wish. You can always rename it later!",
            onShown: function (tour) {
                $("#project-name").val("Demo Project");
            },
        }, {
            // 2
            path: "/new_project.php",
            element: "#faction-list",
            placement: "top",
            title: "Create a Project",
            content: "Name Your Factions. Blank factions will be removed, but you can always add more later.",
            onShown: function (tour) {
                $("#faction-one").val("The Sharks");
                $("#faction-two").val("The Jets");
            }
        }, {
            // 3
            path: "/new_project.php",
            element: "#create-project",
            placement: "top",
            title: "Create a Project",
            content: "Click to create the project. This will take you to the next page.",
            reflex: true,
            next: -1
        }, {
            // 4
            // EDIT PROJECT PAGE
            path: "/edit_project.php",
            orphan: true,
            title: "Welcome to the edit project page!",
            content: "This page lets you configure how many factions there are with a project (up to 12), and how many characters each faction has (up to 12). When you create a project we randomly populate the character names.",
            backdrop: true,
            prev: -1
        }, {
            // 5
            path: "/edit_project.php",
            element: "#project-name",
            placement: "top",
            title: "Edit Project",
            content: "Here you can rename the project.",
        }, {
            // 6
            path: "/edit_project.php",
            element: "#faction_1",
            placement: "top",
            title: "Edit Project",
            content: "This is where you configure characters within a faction.",
            backdrop: true,
        }, {
            // 7
            path: "/edit_project.php",
            element: "[data-char-num='1-1']",
            placement: "top",
            title: "Edit Project",
            content: "Here you can change the character's priority, name, enter the name of the player playing this character, as well as the characters bio.",
            backdrop: true,
        }, {
            // 8
            path: "/edit_project.php",
            element: "#character_priority_1",
            placement: "top",
            title: "Edit Project",
            content: "Assigning characters to A, B, C and D helps determine the fill priorities. This is how important the character is to the game.",
        }, {
            // 9
            path: "/edit_project.php",
            element: "#faction_name_1",
            placement: "top",
            title: "Edit Project",
            content: "Here you can change the name of the faction."
        }, {
            // 10
            path: "/edit_project.php",
            element: "#delete_faction_1",
            placement: "top",
            title: "Edit Project",
            content: "Clicking here will disable the faction. Go ahead and disable the faction now or click next to skip this part.",
            next: 13
        }, {
            // 11
            path: "/edit_project.php",
            element: "#deleted-factions",
            placement: "top",
            title: "Edit Project",
            content: "Clicking on the restore icon &nbsp;<i class='fa fa-undo fa-lg'></i>&nbsp; will enable the faction you just disabled. Go ahead and enable the faction now.",
            next: -1,
            prev: -1,
            backdrop: true,
            onShown: function (tour) {
                $(document).on("click", "#restore_faction_1", function(e) {
                    if (tour.getCurrentStep() == 11) {
                        tour.goTo(12);
                    }
                });
            }
        }, {
            // 12
            path: "/edit_project.php",
            orphan: true,
            title: "Edit Project",
            content: "Excellent! The faction has been restored.",
            prev: 10
        }, {
            // 13
            path: "/edit_project.php",
            element: "[data-character-num='1']",
            placement: "top",
            title: "Edit Project",
            content: "Clicking on this will allow you to disable or enable the character simliar to disabling or enabling a faction.",
        }, {
            // 14
            path: "/edit_project.php",
            element: "#add-faction",
            placement: "top",
            title: "Edit Project",
            content: "Here you can add another faction to your project. Each project can have 12 factions. Each faction can have 12 characters. That is up to 144 characters!",
        }, {
            // 15
            path: "/edit_project.php",
            element: "#faction-count",
            placement: "top",
            title: "Edit Project",
            content: "Here you can see how many factions you have active. Remember: You can only have 12 factions per project whether they are active or not.",
        }, {
            // 16
            path: "/edit_project.php",
            element: "#random-names",
            placement: "top",
            title: "Edit Project",
            content: "Clicking this will clear out all the character names and fill them with random names. The changes will only be saved if you click the Save Project button.",
        }, {
            // 17
            path: "/edit_project.php",
            element: "#clear-character-names",
            placement: "top",
            title: "Edit Project",
            content: "These three options allow you to clear the character names, player names or the bio fields.",
            onHidden: function(tour) {
                if (user_viewed_tutorial) {
                    tour.redirect('false');
                }
            }
        }, {
            // 18
            path: "/edit_project.php",
            element: "#nav-grid",
            placement: "bottom",
            title: "Edit Project",
            content: "When your changes are saved, the Grid page is our next stop. Do not worry about saving now, you can come back later and make changes.",
        }, {
            // 19
            // GRID PAGE
            path: "/grid.php",
            orphan: true,
            title: "Welcome to the Project Grid!",
            content: "This page allows you to set how the faction on the left feels towards the faction on the top.",
            backdrop: true,
        }, {
            // 20
            path: "/grid.php",
            element: "#faction-left",
            placement: "bottom",
            title: "Project Grid",
            content: "This faction will appear on the left side. Each character will be represented by a row on the grid.",
        }, {
            // 21
            path: "/grid.php",
            element: "#faction-top",
            placement: "bottom",
            title: "Project Grid",
            content: "This faction will appear on the top. Each character will be represented by a column on the grid.",
        }, {
            // 22
            path: "/grid.php",
            element: "#faction2faction",
            placement: "bottom",
            title: "Project Grid",
            content: "You can change the left factions opinion of the top faction here.",
            backdrop: true,
        }, {
            // 23
            path: "/grid.php",
            element: "#character_to_faction_1",
            placement: "bottom",
            title: "Project Grid",
            content: "You can change this characters opinion of the top faction here.",
            backdrop: true,
        }, {
            // 24
            path: "/grid.php",
            element: "#block1c",
            placement: "bottom",
            title: "Project Grid",
            content: "You can then click on any box in the grid to change how the character on the left feels towards the character on the top.",
        }, {
            // 25
            path: "/grid.php",
            element: "#character_to_faction_1",
            placement: "bottom",
            title: "Project Grid",
            content: "If a left character does not have a specific opinion of a top character, then the left character's opinion of the top character's faction is used."
        }, {
            // 26
            path: "/grid.php",
            element: "#faction2faction",
            placement: "right",
            title: "Project Grid",
            content: "If the left character has no specific opinion of the top character's faction, then the left faction's opinion of the top faction is used."
        }, {
            // 27
            path: "/grid.php",
            orphan: true,
            title: "Project Grid",
            content: "With up to 12 factions and up to 12 characters per faction, that is 144 characters! There could be over 20,000 different opinions!"
        }, {
            // 28
            path: "/grid.php",
            element: "#rowheader1",
            placement: "right",
            title: "Project Grid",
            content: "Clicking on any character names will open a character card sheet. This applies to both the left characters and the top characters.",
        }, {
            // 29
            path: "/grid.php",
            element: "#character-cards",
            placement: "bottom",
            title: "Project Grid",
            content: "You can also see character cards here.",
        }, {
            // 30
            path: "/grid.php",
            element: "#print-character-cards",
            placement: "bottom",
            title: "Project Grid",
            content: "When your project is complete, you can click here to print or download all the information as a PDF.",
            onHidden: function(tour) {
                if (user_viewed_tutorial) {
                    tour.redirect('false');
                }
            }
        }, {
            // 31
            path: "/load.php",
            orphan: true,
            title: "Open Project",
            content: "As you create new projects, you can come here to open your saved projects or delete old projects. When deleting projects from this page, all the data will be deleted and cannot be undone."
        }, {
            // 32
            path: "/new_project.php",
            orphan: true,
            title: "Tour Complete!",
            content: "This concludes the tutorial for Factionizer. If you want to go through the tutorial again, come back to this page and click the 'Show the Tour' button on the right. Most pages have the same button that will allow you to start the Tour for that page. We hope you enjoy using Factionizer!"
        }
        ],
        onEnd: function (tour) {
            $.get("admin_users.php?action=tutorial_end&user_id=" + user_id, function(data) {});
        }
    }).init();
    if (user_viewed_tutorial == 0) {
        tour.start(true);
    }

    $(document).on("click", "[data-tour]", function(e) {
        e.preventDefault();
        tour.restart();
    });
    $(document).on("click", "#tour-start-edit", function(e) {
        e.preventDefault();
        tour.setCurrentStep(4);
        tour.start(true);
    });
    $(document).on("click", "#tour-start-grid", function(e) {
        e.preventDefault();
        tour.setCurrentStep(19);
        tour.start(true);
    });
    $(document).on("click", "#create-project", function(e) {
        if ( ! tour.ended() && tour.getCurrentStep() == 3) {
            tour.goTo(4);
        }
    });
    $(document).on("click", "#delete_faction_1", function(e) {
        if ( ! tour.ended() && tour.getCurrentStep() == 10) {
            tour.goTo(11);
        }
    });
});