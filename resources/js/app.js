import $ from "jquery";
import "summernote/dist/summernote-lite.js"; // Non-Bootstrap version
import "summernote/dist/summernote-lite.css"; // Include the CSS for this version
import Swal from "sweetalert2";

import Alpine from "alpinejs";
window.$ = $;
window.jQuery = $;

window.Alpine = Alpine;
Alpine.start();

window.initialize = initialize;

// Initialize Google Maps after the page is loaded
document.addEventListener("DOMContentLoaded", function () {
    initialize(); // Call here if needed
});

// Format currency helper
window.formatCurrency = function (value) {
    return new Intl.NumberFormat("en-GB", {
        style: "currency",
        currency: "GBP",
    }).format(value);
};

$(document).ready(function () {
    // Accordion functionality
    $("[data-accordion-target]").click(function () {
        const isExpanded = $(this).attr("aria-expanded") === "true";
        const accordionBody = $(this).attr("data-accordion-target");

        $(this).find("svg.icon").toggleClass("rotate-180");

        if (isExpanded) {
            $(this).attr("aria-expanded", "false");
            $(accordionBody).slideUp().addClass("hidden");
        } else {
            $(accordionBody).slideDown().removeClass("hidden");
            $(this).attr("aria-expanded", "true");
        }
    });

    // Hide accordion content by default
    $(".accordion-content").hide();

    $(".accordion-item .accordion-title").click(function () {
        // Toggle active class to show/hide accordion content
        $(this).parent().toggleClass("active");
        $(this).parent().find(".accordion-content").slideToggle();
        $(".accordion-item")
            .not($(this).parent())
            .removeClass("active")
            .find(".accordion-content")
            .slideUp();

        // Prevent checkbox from being checked/unchecked when clicking on label
        var checkbox = $(this).siblings('input[type="checkbox"]');
        checkbox.prop("checked", !checkbox.prop("checked"));
    });

    // Function to close all accordion items
    function closeAllAccordions() {
        $(".accordion-item").removeClass("active");
        $(".accordion-content").slideUp().addClass("hidden");
    }

    // Click outside to close the accordion`
    $(document).click(function (event) {
        // Check if the click is outside the accordion
        if (
            !$(event.target).closest(".accordion-item, [data-accordion-target]")
                .length
        ) {
            closeAllAccordions();
        }
    });

    // Prevent clicks inside the accordion from closing it
    $(".accordion-item").click(function (event) {
        event.stopPropagation();
    });

    // Hide all tab contents except the first one
    $(".venue-tab-content > div:not(:first)").hide();

    // Add active class to the default tab link
    $(".tabLinks:first").addClass(
        "active text-yns_yellow border-b-2 border-yns_yellow rounded-t-lg group"
    );

    // Add click event to tab links
    $(".tabLinks").click(function () {
        // Get the tab ID from the data attribute
        var tabId = $(this).data("tab");

        // Hide all tab contents
        $(".venue-tab-content > div").hide();

        // Show the selected tab content
        $("#" + tabId).fadeIn();

        // Remove "active" class from all tab links
        $(".tabLinks").removeClass(
            "active text-yns_yellow border-b-2 border-yns_yellow rounded-t-lg group"
        );

        // Add "active" class to the clicked tab link
        $(this).addClass(
            "active text-yns_yellow border-b-2 border-yns_yellow rounded-t-lg group"
        );

        // Prevent default link behavior
        return false;
    });
});

// Review Modal JS
document.addEventListener("DOMContentLoaded", function () {
    // Function to show the modal
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        const backdrop = document.querySelector(".fixed");

        if (modal && backdrop) {
            modal.classList.remove("hidden");
            backdrop.setAttribute("aria-hidden", "false");
            modal.focus();
        }
    }

    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        const backdrop = document.querySelector(".fixed");

        if (modal && backdrop) {
            modal.classList.add("hidden");
            backdrop.setAttribute("aria-hidden", "true");
        }
    }
    // Event listener for buttons to show the modal
    document.querySelectorAll("[data-modal-toggle]").forEach((button) => {
        button.addEventListener("click", function () {
            const modalId = this.getAttribute("data-modal-toggle");
            showModal(modalId);
        });
    });

    // Event listener for modal close buttons
    document.querySelectorAll("[data-modal-hide]").forEach((button) => {
        button.addEventListener("click", function () {
            const modalId = this.getAttribute("data-modal-hide");
            hideModal(modalId);
        });
    });

    // Close modal when clicking outside of it
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("fixed")) {
            hideModal(event.target.id);
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const emptyIcon = "{{ asset('storage/images/system/ratings/empty.png') }}";
    const fullIcon = "{{ asset('storage/images/system/ratings/full.png') }}";
    const hotIcon = "{{ asset('storage/images/system/ratings/hot.png') }}";

    const checkboxes = document.querySelectorAll(
        '.rating input[type="checkbox"]'
    );

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            const value = parseInt(this.value);
            const group = this.name;

            const checkboxesInGroup = document.querySelectorAll(
                `input[name="${group}"]`
            );

            // Check if 5/5 is selected
            if (value === 5) {
                checkboxesInGroup.forEach((cb) => {
                    if (parseInt(cb.value) === 5) {
                        cb.checked = true;
                    }
                    cb.nextElementSibling.style.backgroundImage = `url('${hotIcon}')`;
                });
            } else {
                // Update the checkboxes based on selected value
                checkboxesInGroup.forEach((cb) => {
                    if (parseInt(cb.value) <= value) {
                        cb.checked = true;
                        cb.nextElementSibling.style.backgroundImage = `url('${fullIcon}')`;
                    } else {
                        cb.checked = false;
                        cb.nextElementSibling.style.backgroundImage = `url('${emptyIcon}')`;
                    }
                });
            }
        });
    });
});

// Reviewer IP
$(document).ready(function () {
    $.getJSON("https://api.ipify.org?format=json", function (data) {
        var userIP = data.ip;
        // Verify the element exists before setting the value
        var reviewerIpField = $("#reviewer_ip");
        if (reviewerIpField.length) {
            reviewerIpField.val(userIP);
        }
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.error("Request Failed: " + err);
    });
});

// Function to initialize Summernote
window.initialiseSummernote = function (selector, initialContent) {
    console.log("Initializing summernote..."); // Debug line
    $(selector).summernote({
        height: 300,
        toolbar: [
            ["style", ["style"]],
            ["font", ["bold", "italic", "underline", "clear"]],
            ["fontname", ["fontname"]],
            ["fontsize", ["fontsize"]],
            ["fontSizeUnits", ["px", "pt"]],
            ["color", ["color"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["insert", ["link", "picture", "video"]],
            ["view", ["fullscreen", "help"]],
        ],
        callbacks: {
            onInit: function () {
                $(this).summernote("code", initialContent); // Set the initial content
            },
            onKeyup: function () {
                var editor = $(this);
                var content = editor.summernote("code");

                // Analyze and get the highlighted content
                var highlightedContent = analyzeText(content);

                // Update only if the content has changed
                if (highlightedContent !== content) {
                    // Get the current selection before updating the content
                    var selection = window.getSelection();
                    var range = selection.getRangeAt(0);

                    // Update the content directly
                    editor.summernote("code", highlightedContent);

                    // Restore the selection
                    setTimeout(function () {
                        // Get the editable area
                        var $editable = editor.summernote("editable")[0];

                        // Set the cursor position back to where it was
                        selection.removeAllRanges(); // Clear existing selections
                        selection.addRange(range); // Set the new range

                        // Refocus on the editor
                        $editable.focus(); // Focus the editable area
                    }, 0); // Use a small delay to ensure the content is rendered before moving the cursor
                }
            },
        },
    });
};

// Function to analyze text for venue names
function analyzeText(inputText) {
    const venues = [
        {
            name: "The Forum",
            link: "https://www.google.com/theforummusiccenter",
        },
        { name: "The Turks Head", link: "https://www.google.com/theturkshead" },
    ];

    let highlightedContent = inputText; // Start with the original input text

    venues.forEach((venue) => {
        const regex = new RegExp(`\\b(${venue.name})\\b`, "gi");
        highlightedContent = highlightedContent.replace(
            regex,
            `<span class="highlight" data-link="${venue.link}">$1</span>`
        );
    });
    return highlightedContent; // Return the modified content
}

// Sweet Alert 2 Notifications
window.showSuccessNotification = function (message) {
    Swal.fire({
        showConfirmButton: false,
        toast: true,
        position: "top-end",
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: "bg-yns_dark_gray !important rounded-lg font-heading",
            title: "text-black",
            html: "text-black",
        },
        icon: "success",
        title: "Success!",
        text: message,
    });
};

window.showFailureNotification = function (message) {
    Swal.fire({
        showConfirmButton: false,
        toast: true,
        position: "top-end",
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: "bg-yns_dark_gray !important rounded-lg font-heading",
            title: "text-black",
            html: "text-black",
        },
        icon: "error",
        title: "Oops!",
        text: message,
    });
};

// Address Input
function initialize() {
    // All your Google Maps initialization code
    $("form").on("keyup keypress", function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    const locationInputs = document.getElementsByClassName("map-input");
    const autocompletes = [];
    const geocoder = new google.maps.Geocoder();

    for (let i = 0; i < locationInputs.length; i++) {
        const input = locationInputs[i];
        const fieldKey = input.id.replace("-input", "");
        const isEdit =
            document.getElementById(fieldKey + "-latitude").value != "" &&
            document.getElementById(fieldKey + "-longitude").value != "";

        const latitude =
            parseFloat(document.getElementById(fieldKey + "-latitude").value) ||
            59.339024834494886;
        const longitude =
            parseFloat(
                document.getElementById(fieldKey + "-longitude").value
            ) || 18.06650573462189;

        const map = new google.maps.Map(
            document.getElementById(fieldKey + "-map"),
            {
                center: { lat: latitude, lng: longitude },
                zoom: 13,
            }
        );

        const marker = new google.maps.Marker({
            map: map,
            position: { lat: latitude, lng: longitude },
        });

        marker.setVisible(isEdit);

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.key = fieldKey;
        autocompletes.push({
            input: input,
            map: map,
            marker: marker,
            autocomplete: autocomplete,
        });
    }

    // Set up listeners for each autocomplete
    autocompletes.forEach(({ input, autocomplete, map, marker }) => {
        google.maps.event.addListener(
            autocomplete,
            "place_changed",
            function () {
                marker.setVisible(false);
                const place = autocomplete.getPlace();

                let postalTown = "";
                place.address_components.forEach((component) => {
                    if (component.types.includes("postal_town")) {
                        postalTown = component.long_name;
                    }
                });

                const postalTownComponent = place.address_components.find(
                    (component) => component.types.includes("postal_town")
                );
                if (postalTownComponent) {
                    document.getElementById("postal-town-input").value =
                        postalTownComponent.long_name;
                }

                geocoder.geocode(
                    { placeId: place.place_id },
                    function (results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            const lat = results[0].geometry.location.lat();
                            const lng = results[0].geometry.location.lng();
                            setLocationCoordinates(autocomplete.key, lat, lng);
                        }
                    }
                );

                if (!place.geometry) {
                    window.alert(
                        "No details available for input: '" + place.name + "'"
                    );
                    input.value = "";
                    return;
                }

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
            }
        );
    });
}

function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-latitude");
    const longitudeField = document.getElementById(key + "-longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
}

// Full Calendar
document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("calendar");
    var userId = calendarEl.getAttribute("data-user-id"); // Get user ID from data attribute

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        events: function (fetchInfo, successCallback, failureCallback) {
            fetch(`/api/profile/${userId}`, {
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Authorization: "Bearer " + localStorage.getItem("token"), // Use your auth token
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        successCallback(data.events); // Pass the events to the calendar
                    } else {
                        console.log("Error: ", data.message);
                        failureCallback(data.message);
                    }
                })
                .catch((error) => {
                    console.error("Fetch error: ", error);
                    failureCallback(error);
                });
        },
        dateClick: function (info) {
            alert("Date: " + info.dateStr);
        },
        eventClick: function (info) {
            alert("Event: " + info.event.title);
        },
    });
    calendar.render();
});
