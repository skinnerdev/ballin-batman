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
            title: "Create a Project Name",
            content: "Here you can enter a name for your project. We've given it a name for now. You can change the name now if you wish. You can always rename it later!",
            onShown: function (tour) {
                $("#project-name").val("Demo Project");
            },
        }, {
            // 2
            path: "/new_project.php",
            element: "#faction-list",
            placement: "top",
            title: "Name the Factions",
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
            title: "Click Create",
            content: "Click below to create the project. This will take you to the next page.",
            reflex: true,
            next: -1
        }, {
            // 4
            // EDIT PROJECT PAGE
            path: "/edit_project.php",
            orphan: true,
            title: "The Names Page",
            content: "This page lets you configure how many factions there are with a project (up to 12), and how many characters each faction has (up to 12). When you create a project we randomly populate the character names.",
            backdrop: true,
            prev: -1
        }, {
            // 5
            path: "/edit_project.php",
            element: "#project-name",
            placement: "top",
            title: "Rename the Project",
            content: "Here you can rename the project.",
        }, {
            // 6
            path: "/edit_project.php",
            element: "#faction_1",
            placement: "top",
            title: "Edit Characters",
            content: "This is where you configure characters within a faction.",
            backdrop: true,
        }, {
            // 7
            path: "/edit_project.php",
            element: "[data-char-num='1-1']",
            placement: "top",
            title: "Edit Characters",
            content: "Here you can change the character's priority, name, and the name of the player playing this character.",
            backdrop: true,
        }, {
            // 8
            path: "/edit_project.php",
            element: "#character_priority_1",
            placement: "top",
            title: "Assign Priority",
            content: "Assigning characters to A, B, C and D helps determine the fill priorities. This is how important the character is to the game.",
        }, {
            // 9
            path: "/edit_project.php",
            element: "#faction_name_1",
            placement: "top",
            title: "Edit Faction",
            content: "Here you can change the name of the faction."
        }, {
            // 10
            path: "/edit_project.php",
            element: "#delete_faction_1",
            placement: "top",
            title: "Disable Faction",
            content: "Clicking here will disable the faction. Go ahead and disable the faction now or click next to skip this part. You can always recover a disabled faction.",
            next: 13
        }, {
            // 11
            path: "/edit_project.php",
            element: "#deleted-factions",
            placement: "top",
            title: "Restore Faction",
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
            title: "Success!",
            content: "Excellent! The faction has been restored.",
        }, {
            // 13
            path: "/edit_project.php",
            element: "[data-character-num='1']",
            placement: "top",
            title: "Delete and Re-Enable Characters",
            content: "Clicking on this will allow you to disable or enable the character similar to disabling or enabling a faction. You can always recover a disabled faction or character.",
            prev: 10
        }, {
            // 14
            path: "/edit_project.php",
            element: "#add-faction",
            placement: "top",
            title: "Add Faction",
            content: "Here you can add another faction to your project. Each project can have 12 factions. Each faction can have 12 characters. That is up to 144 characters!",
        }, {
            // 15
            path: "/edit_project.php",
            element: "#faction-count",
            placement: "top",
            title: "Faction Count",
            content: "Here you can see how many factions you have active. Remember: You can only have 12 factions per project whether they are active or not.",
        }, {
            // 16
            path: "/edit_project.php",
            element: "#random-names",
            placement: "top",
            title: "Clear Character Names",
            content: "Clicking this will clear all the character names and fill them with random names. The changes will only be saved if you click the Save Project button.",
        }, {
            // 17
            path: "/edit_project.php",
            element: "#clear-all-names",
            placement: "top",
            title: "Clear All Names",
            content: "Clicking this will clear all the character and faction names.",
        }, {
            // 18
            path: "/edit_project.php",
            element: "#clear-player-names",
            placement: "top",
            title: "Clear Player Names",
            content: "Clicking this will clear player names.",
        }, {
            // 19
            path: "/edit_project.php",
            element: "#save-project",
            placement: "top",
            title: "Save Project",
            content: "You can click here to save your changes.",
            onShow: function(tour) {
                if (user_viewed_tutorial) {
                    this.next = -1;
                    this.keyboard = false;
                }
            }
        }, {
            // 20
            path: "/edit_project.php",
            element: "#nav-grid",
            placement: "bottom",
            title: "Save Project Now",
            content: "The Grid page is our next stop. Hit save now, and remember you can always come back later to make changes.",
        }, {
            // 21
            // GRID PAGE
            path: "/grid.php",
            orphan: true,
            title: "Welcome to the Project Grid!",
            content: "This page allows you to set how the faction on the left feels towards the faction on the top.",
            backdrop: true,
            onShow: function(tour) {
                if (user_viewed_tutorial) {
                    this.prev = -1;
                    this.keyboard = false;
                }
            }
        }, {
            // 22
            path: "/grid.php",
            element: "#faction-left",
            placement: "bottom",
            title: "Left Faction",
            content: "This faction will appear on the left side. Each character will be represented by a row on the grid.",
        }, {
            // 23
            path: "/grid.php",
            element: "#faction-top",
            placement: "bottom",
            title: "Top Faction",
            content: "This faction will appear on the top. Each character will be represented by a column on the grid.",
        }, {
            // 24
            path: "/grid.php",
            element: "#faction2faction",
            placement: "bottom",
            title: "How Faction Feels About Faction",
            content: "You can change the left faction's opinion of the top faction here. This is included in all character sheets.",
            backdrop: true,
        }, {
            // 25
            path: "/grid.php",
            element: "#character_to_faction_1",
            placement: "bottom",
            title: "How Character Feels About Faction",
            content: "You can change the Left character's opinion of the top Faction here.",
            backdrop: true,
        }, {
            // 26
            path: "/grid.php",
            element: "#block1c",
            placement: "bottom",
            title: "How Character Feels About Character",
            content: "You can then click on any box in the grid to change how the left character feels about the top character.",
        }, {
            // 27
            path: "/grid.php",
            element: "#character_to_faction_1",
            placement: "bottom",
            title: "No Opinion?",
            content: "If a left character does not have a specific opinion of a top character, then the left character's opinion of the top character's faction is used. If there is an opinion, you'll see the more specific opinion here, but the general opinion will still appear on the character sheet."
        }, {
            // 28
            path: "/grid.php",
            element: "#faction2faction",
            placement: "right",
            title: "Still No Opinion?",
            content: "If the left character has no specific opinion of the top character's faction, then the left faction's opinion of the top faction is shown. If there is an opinion, it will be included on the character sheet along with the other opinions."
        }, {
            // 29
            path: "/grid.php",
            orphan: true,
            title: "Huge LARPs",
            content: "With up to 12 factions and up to 12 characters per faction, you can run a LARP with up to 144 characters, and over 20,000 different unique opinions!"
        }, {
            // 30
            path: "/grid.php",
            element: "#rowheader1",
            placement: "right",
            title: "Character Cards",
            content: "Clicking on any character names will open a character card sheet. This applies to both the left characters and the top characters.",
        }, {
            // 31
            path: "/grid.php",
            element: "#character-cards",
            placement: "bottom",
            title: "Character Cards",
            content: "You can also see character cards here.",
        }, {
            // 32
            path: "/grid.php",
            orphan: true,
            title: "Biography and Other Info",
            content: "If you select the same faction for top and bottom, you can edit a character's bio. You will see the same character on the top and the left site. The intersecting cell will contain the word 'SELF'. Clicking on this cell will allow you to edit the biography of the character.",
        }, {
            // 33
            path: "/grid.php",
            element: "#print-character-cards",
            placement: "bottom",
            title: "Print It All Out",
            content: "When your project is complete, you can click here to print or download all the information as a PDF.",
            onShow: function(tour) {
                if (user_viewed_tutorial) {
                    this.next = -1;
                    this.keyboard = false;
                }
            }
        }, {
            // 34
            path: "/load.php",
            orphan: true,
            title: "Load and Delete Projects",
            content: "As you create new projects, you can come here to open your saved projects or delete old projects. When deleting projects from this page, all the data will be deleted and cannot be undone."
        }, {
            // 35
            path: "/new_project.php",
            orphan: true,
            title: "Tour Complete!",
            content: "This concludes the tutorial for Factionizer. If you want to go through the tutorial again, come back to this page and click the 'Show the Tour' button on the right. Most pages have the same button that will allow you to start the Tour for that page. We hope you enjoy using Factionizer!"
        }
        ],
        onEnd: function (tour) {
            $.get("admin_users.php?action=tutorial_end&user_id=" + user_id, function(data) {});
        }
    });
    if (user_viewed_tutorial == 0) {
        tour.init();
        tour.start(true);
    }

    $(document).on("click", "[data-tour]", function(e) {
        e.preventDefault();
        tour.restart();
    });
    $(document).on("click", "#tour-start-edit", function(e) {
        e.preventDefault();
        tour.init();
        tour.setCurrentStep(4);
        tour.start(true);
    });
    $(document).on("click", "#tour-start-grid", function(e) {
        e.preventDefault();
        tour.init();
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