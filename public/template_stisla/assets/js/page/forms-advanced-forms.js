"use strict";

$(".daterange-cus").daterangepicker({
  locale: { format: "YYYY-MM-DD" },
  drops: "down",
  opens: "right",
});
$(".daterange-btn").daterangepicker(
  {
    ranges: {
      All: [moment("2024-01-01"), moment()],
      Today: [moment(), moment()],
      Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
      "Last 7 Days": [moment().subtract(6, "days"), moment()],
      "Last 30 Days": [moment().subtract(29, "days"), moment()],
      "This Month": [moment().startOf("month"), moment().endOf("month")],
      "Last Month": [
        moment().subtract(1, "month").startOf("month"),
        moment().subtract(1, "month").endOf("month"),
      ],
    },
    startDate: moment("2024-01-01"),
    endDate: moment(),
  },
  function (start, end) {
    var startDay = start.format("dddd");
    var startDate = start.format("D");
    var startMonth = start.format("MMMM");
    var endDay = end.format("dddd");
    var endDate = end.format("D");
    var endMonth = end.format("MMMM");
    var daysOfWeek = {
      Sunday: "Minggu",
      Monday: "Senin",
      Tuesday: "Selasa",
      Wednesday: "Rabu",
      Thursday: "Kamis",
      Friday: "Jumat",
      Saturday: "Sabtu",
    };
    var months = {
      January: "Januari",
      February: "Februari",
      March: "Maret",
      April: "April",
      May: "Mei",
      June: "Juni",
      July: "Juli",
      August: "Agustus",
      September: "September",
      October: "Oktober",
      November: "November",
      December: "Desember",
    };
    var formattedStartDateTime =
      daysOfWeek[startDay] +
      ", " +
      startDate +
      " " +
      months[startMonth] +
      " " +
      start.format("YYYY");
    var formattedEndDateTime =
      daysOfWeek[endDay] +
      ", " +
      endDate +
      " " +
      months[endMonth] +
      " " +
      end.format("YYYY");

    var financialData = JSON.parse($("input[id='data']").val());
    financialData.income = Object.values(financialData.income).map((data) => {
      data.payment_list = data.payment_list.filter((payment) => {
        const date = new Date(payment.date);
        return date >= start && date <= end;
      });
      return data;
    });

    financialData.outcome = Object.values(financialData.outcome).map((data) => {
      data.payment_list = data.payment_list.filter((payment) => {
        console.log("payment.date outcome", payment.date);
        const date = new Date(payment.date);
        return date >= start && date <= end;
      });
      return data;
    });

    const allSelectElement = document.querySelectorAll("select");
    const incomeSelect = allSelectElement[0];
    const outcomeSelect = allSelectElement[1];

    for (let i = incomeSelect.options.length - 1; i > 0; i--) {
      incomeSelect.remove(i);
    }
    for (let i = outcomeSelect.options.length - 1; i > 0; i--) {
      outcomeSelect.remove(i);
    }
    populateSelect(incomeSelect, financialData.income);
    populateSelect(outcomeSelect, financialData.outcome);

    function populateSelect(selectElement, data) {
      // Clear existing options
      console.log("data:", data);
      Object.values(data).forEach((item) => {
        let total_amount = 0;
        Object.values(item.payment_list).forEach((payment) => {
          total_amount += parseFloat(payment.amount);
        });
        const option = document.createElement("option");
        option.value = item.financial_id;
        option.textContent =
          item.financial_details + " - Rp. " + total_amount.toLocaleString();
        selectElement.appendChild(option);
      });
    }
    
    $(".daterange-btn span").html(
      formattedStartDateTime + " - " + formattedEndDateTime
    );
    $('input[id="daterange-data"]').val(
      start.toISOString() + " - " + end.toISOString()
    );
  }
);
