// new TomSelect("#select-multiple", {
//     plugins: {
//         remove_button: {
//             title: "Remove this item",
//         },
//     },
//     maxItems: null,
//     valueField: "id",
//     searchField: "title",
//     options: [
//         {
//             id: 1,
//             title: "Nursahid Arya Suyudi",
//             url: "18630523",
//         },
//         {
//             id: 2,
//             title: "Rania Nor Aida",
//             url: "18630524",
//         },
//         {
//             id: 3,
//             title: "Nurcholis",
//             url: "18630525",
//         },
//     ],
//     render: {
//         option: function (data, escape) {
//             return (
//                 "<div>" +
//                 '<span class="title">' +
//                 escape(data.title) +
//                 "</span>" +
//                 '<span class="url">' +
//                 escape(data.url) +
//                 "</span>" +
//                 "</div>"
//             );
//         },
//         item: function (data, escape) {
//             return (
//                 '<div title="' +
//                 escape(data.url) +
//                 '">' +
//                 escape(data.title) +
//                 "</div>"
//             );
//         },
//     },
// });
