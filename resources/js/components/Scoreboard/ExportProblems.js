const Excel = require("exceljs");

const mapDataMyScore = (user_id, scoreboard) => {
    const convert = arr => {
        const res = {};
        arr.forEach(obj => {
            const key = `${obj.schedule_id}`;
            if (!res[key]) {
                res[key] = { ...obj, count: 0 };
            }
            res[key].count += 1;
        });
        return Object.values(res);
    };
    return convert(
        user_id ? scoreboard.filter(e => e.user_id === user_id) : scoreboard
    );
};

const exportProblems = (schedule, classroom, scoreboard, classroom_name) => {
    const columns = [
        { header: "No", key: "No" },
        { header: "Name", key: "Name" },
        { header: "Username", key: "Username" }
    ];

    schedule.forEach(e => {
        columns.push({
            header: `${e.title} (${e.score})`,
            key: e.title.replace(" ", "")
        });
    });

    const workbook = new Excel.Workbook();
    const workSheet = workbook.addWorksheet(classroom_name);

    workSheet.columns = columns;
    workSheet.getRow(1).font = { bold: true };
    for (var c = 1; c <= columns.length; c++) {
        workSheet.getCell(1, c).fill = {
            type: "pattern",
            pattern: "solid",
            fgColor: { argb: "70AD47" }
        };
    }

    workSheet.columns.forEach((col, i) => {
        col.alignment = { horizontal: "center", vertical: "middle" };
        col.width = col.header !== undefined ? col.header.length + 10 : 0;
    });

    let row = 0;

    classroom.forEach((data, index) => {
        let rowObj = {
            No: String(index + 1),
            Name: data.name,
            Username: data.username
        };

        schedule.forEach((sch, index) => {
            const scoreBoard = mapDataMyScore(data.user_id, scoreboard);

            const res = scoreBoard.find(e => e.schedule_id === sch.id);
            rowObj[sch.title.replace(" ", "")] = res ? res.score : "-";
        });
        workSheet.addRow(rowObj);
        row += 1;

        workSheet.eachRow({ includeEmpty: true }, function(row) {
            row.eachCell({ includeEmpty: true }, function(cell) {
                cell.border = {
                    top: { style: "thin" },
                    left: { style: "thin" },
                    bottom: { style: "thin" },
                    right: { style: "thin" }
                };
            });
        });
    });

    return workbook.xlsx.writeBuffer();
};

const exportProblemsDetail = (
    schedule,
    classroom,
    scoreboard,
    classroom_name
) => {
    const columns = [
        { header: "No", key: "No" },
        { header: "Name", key: "Name" },
        { header: "Username", key: "Username" },
        { header: "Problems", key: "Problems" },
        { header: "Date Submission", key: "DateSubmission" },
        { header: "Count Submit", key: "countSubmit" },
        { header: "Score", key: "Score" }
    ];

    const getCellAddress = (workSheet, row, column) => {
        let address = "";
        workSheet.getRow(row).eachCell((e, index_cell) => {
            if (index_cell === column) {
                address = e.address;
            }
        });
        return address;
    };

    const workbook = new Excel.Workbook();
    const workSheet = workbook.addWorksheet(classroom_name);

    workSheet.columns = columns;

    workSheet.getRow(1).font = { bold: true };
    for (var c = 1; c <= columns.length; c++) {
        workSheet.getCell(1, c).fill = {
            type: "pattern",
            pattern: "solid",
            fgColor: { argb: "70AD47" }
        };
    }

    workSheet.columns.forEach((col, i) => {
        col.alignment = { horizontal: "center", vertical: "middle" };
        col.width = col.header !== undefined ? col.header.length + 10 : 0;
    });

    let row = 0;

    classroom.forEach((data, index) => {
        const scoreBoard = mapDataMyScore(data.user_id, scoreboard);

        if (scoreBoard.length > 0) {
            scoreBoard.forEach((info, i) => {
                let rowObj = {
                    No: String(index + 1),
                    Name: data.name,
                    Username: data.username,
                    Problems: schedule.find(e => e.id === info.schedule_id)
                        .title,
                    countSubmit: info.count,
                    DateSubmission: info.date_send,
                    Score: info.score
                };
                workSheet.addRow(rowObj);
                row += 1;

                if (i === scoreBoard.length - 1) {
                    rowObj.No = "Summary";
                    rowObj.Name = "";
                    rowObj.Username = "";
                    rowObj.Problems = "";
                    rowObj.DateSubmission = "";
                    rowObj.countSubmit = scoreBoard.reduce(
                        (t, n) => t + n.count,
                        0
                    );
                    rowObj.Score = scoreBoard.reduce((t, n) => t + n.score, 0);

                    workSheet.addRow(rowObj);
                    row += 1;

                    workSheet.getRow(row + 1).font = { bold: true };
                    for (var c = 1; c <= columns.length; c++) {
                        workSheet.getCell(row + 1, c).fill = {
                            type: "pattern",
                            pattern: "solid",
                            fgColor: { argb: "FFE4C2" }
                        };
                    }

                    workSheet.mergeCells(
                        `${getCellAddress(
                            workSheet,
                            row + 1 - scoreBoard.length,
                            1
                        )}:${getCellAddress(workSheet, row, 1)}`
                    );
                    workSheet.mergeCells(
                        `${getCellAddress(
                            workSheet,
                            row + 1 - scoreBoard.length,
                            2
                        )}:${getCellAddress(workSheet, row, 2)}`
                    );
                    workSheet.mergeCells(
                        `${getCellAddress(
                            workSheet,
                            row + 1 - scoreBoard.length,
                            3
                        )}:${getCellAddress(workSheet, row, 3)}`
                    );
                }
            });
        } else {
            let rowObj = {
                No: String(index + 1),
                Name: data.name,
                Username: data.username,
                Problems: "-",
                DateSubmission: "-",
                countSubmit: "-",
                Score: "-"
            };
            workSheet.addRow(rowObj);
            row += 1;
        }

        workSheet.eachRow({ includeEmpty: true }, function(row) {
            row.eachCell({ includeEmpty: true }, function(cell) {
                cell.border = {
                    top: { style: "thin" },
                    left: { style: "thin" },
                    bottom: { style: "thin" },
                    right: { style: "thin" }
                };
            });
        });
    });

    return workbook.xlsx.writeBuffer();
};

module.exports = {
    exportProblemsDetail,
    exportProblems
};
