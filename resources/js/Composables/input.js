import dayjs from "dayjs";
export function userInputFormat() {
    const dateFormat = (config) => {
        return {
            altFormat: "d/m/Y",
            altInput: true,
            dateFormat: "Y-m-d",
            allowInput: true,
            ...config,
        };
    };

    return {
        dateFormat,
    };
}
