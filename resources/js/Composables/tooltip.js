import { onMounted, onUnmounted } from "vue";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css";

export function useTooltip(content = null) {
    const tooltips = [];

    onMounted(() => {
        const tooltipTriggerList = document.querySelectorAll('[data-target="tooltip_trigger"]');
        tooltipTriggerList.forEach((tooltipTriggerEl) => {
            if (!tooltipTriggerEl._tippy) { // Check if tooltip has already been initialized
                const tooltip = tippy(tooltipTriggerEl, {
                    content: content || tooltipTriggerEl.getAttribute("data-tooltip-content"),
                    allowHTML: true,
                    boundary: document.body,
                });
                tooltips.push(tooltip);
            }
        });
    });

    onUnmounted(() => {
        tooltips.forEach((tooltip) => tooltip.destroy()); // Use destroy() to remove tooltips
        tooltips.length = 0; // Clear the tooltips array
    });
}
