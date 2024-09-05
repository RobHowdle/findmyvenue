import $ from "jquery";
import "./bootstrap";

import Alpine from "alpinejs";
window.$ = $;

window.Alpine = Alpine;

Alpine.start();

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
});
