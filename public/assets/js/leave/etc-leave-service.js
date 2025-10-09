/**
 * EtcLeaveService - JavaScript client for EtcLeaveServices
 *
 * This service provides methods to interact with the EtcLeaveServices API
 * for managing leave categories and validation.
 */

class EtcLeaveService {
  constructor() {
    this.baseUrl = "/outsource/leave";
    this.categories = [];
    this.loadCategories();
  }

  /**
   * Load all leave categories from API
   */
  async loadCategories() {
    try {
      const response = await fetch(`${this.baseUrl}/categories`);
      const data = await response.json();

      if (data.success) {
        this.categories = data.data;
        return this.categories;
      } else {
        console.error("Error loading categories:", data.message);
        return [];
      }
    } catch (error) {
      console.error("Error loading categories:", error);
      return [];
    }
  }

  /**
   * Get all leave categories
   */
  getCategories() {
    return this.categories;
  }

  /**
   * Get category by ID
   */
  getCategoryById(id) {
    return this.categories.find((category) => category.id == id);
  }

  /**
   * Get category by value
   */
  getCategoryByValue(value) {
    return this.categories.find((category) => category.value === value);
  }

  /**
   * Get categories as options for dropdown
   */
  getCategoriesOptions() {
    return this.categories.map((category) => ({
      value: category.id,
      text: category.name,
      "data-value": category.value,
      "data-max-days": category.max_days,
      "data-requires-cert": category.requires_medical_cert,
      "data-is-paid": category.is_paid,
    }));
  }

  /**
   * Validate leave request
   */
  async validateLeaveRequest(categoryId, requestedDays) {
    try {
      const response = await fetch(`${this.baseUrl}/validate`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
        body: JSON.stringify({
          category_id: categoryId,
          requested_days: requestedDays,
        }),
      });

      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error validating leave request:", error);
      return {
        valid: false,
        message: "Error validating leave request",
      };
    }
  }

  /**
   * Get leave requirements
   */
  async getLeaveRequirements(categoryId) {
    try {
      const response = await fetch(
        `${this.baseUrl}/requirements/${categoryId}`
      );
      const data = await response.json();

      if (data.success) {
        return data.data;
      } else {
        console.error("Error getting requirements:", data.message);
        return null;
      }
    } catch (error) {
      console.error("Error getting requirements:", error);
      return null;
    }
  }

  /**
   * Get paid leave categories only
   */
  getPaidCategories() {
    return this.categories.filter((category) => category.is_paid === true);
  }

  /**
   * Get unpaid leave categories only
   */
  getUnpaidCategories() {
    return this.categories.filter((category) => category.is_paid === false);
  }

  /**
   * Get categories that require medical certificate
   */
  getMedicalCertRequiredCategories() {
    return this.categories.filter(
      (category) => category.requires_medical_cert === true
    );
  }

  /**
   * Get categories with max days limit
   */
  getLimitedDaysCategories() {
    return this.categories.filter((category) => category.max_days !== null);
  }

  /**
   * Get categories without max days limit
   */
  getUnlimitedDaysCategories() {
    return this.categories.filter((category) => category.max_days === null);
  }

  /**
   * Populate dropdown with categories
   */
  populateDropdown(selectElement) {
    if (!selectElement) return;

    // Clear existing options
    selectElement.innerHTML = "";

    // Add default option
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Select Leave Category";
    selectElement.appendChild(defaultOption);

    // Add category options
    this.categories.forEach((category) => {
      const option = document.createElement("option");
      option.value = category.id;
      option.textContent = category.name;
      option.setAttribute("data-value", category.value);
      option.setAttribute("data-max-days", category.max_days || "");
      option.setAttribute("data-requires-cert", category.requires_medical_cert);
      option.setAttribute("data-is-paid", category.is_paid);
      selectElement.appendChild(option);
    });
  }

  /**
   * Show category information
   */
  showCategoryInfo(categoryId, infoElement) {
    if (!infoElement) return;

    const category = this.getCategoryById(categoryId);
    if (!category) {
      infoElement.innerHTML = "";
      return;
    }

    let html = `
            <div class="category-info">
                <h5>${category.name}</h5>
                <p><strong>Description:</strong> ${category.description}</p>
                <p><strong>Max Days:</strong> ${
                  category.max_days || "No limit"
                }</p>
                <p><strong>Medical Certificate:</strong> ${
                  category.requires_medical_cert ? "Required" : "Not required"
                }</p>
                <p><strong>Paid Leave:</strong> ${
                  category.is_paid ? "Yes" : "No"
                }</p>
            </div>
        `;

    infoElement.innerHTML = html;
  }

  /**
   * Validate form data
   */
  async validateForm(formData) {
    const categoryId = formData.category_id;
    const requestedDays = parseInt(formData.requested_days) || 0;

    if (!categoryId) {
      return {
        valid: false,
        message: "Please select a leave category",
      };
    }

    if (requestedDays < 1) {
      return {
        valid: false,
        message: "Please enter valid number of days",
      };
    }

    return await this.validateLeaveRequest(categoryId, requestedDays);
  }
}

// Global instance
window.etcLeaveService = new EtcLeaveService();

// jQuery integration
$(document).ready(function () {
  // Initialize dropdown when page loads
  const categorySelect = document.getElementById("leave_category");
  if (categorySelect) {
    window.etcLeaveService.populateDropdown(categorySelect);
  }

  // Handle category change
  $(document).on("change", "#leave_category", function () {
    const categoryId = $(this).val();
    const infoElement = document.getElementById("category-info");

    if (window.etcLeaveService) {
      window.etcLeaveService.showCategoryInfo(categoryId, infoElement);
    }
  });

  // Handle form validation
  $(document).on("submit", "#leave-form", async function (e) {
    e.preventDefault();

    const formData = {
      category_id: $("#leave_category").val(),
      requested_days: $("#requested_days").val(),
      leave_date: $("#leave_date").val(),
      end_leave_date: $("#end_leave_date").val(),
      reason: $("#reason").val(),
    };

    if (window.etcLeaveService) {
      const validation = await window.etcLeaveService.validateForm(formData);

      if (validation.valid) {
        // Submit form
        this.submit();
      } else {
        alert(validation.message);
      }
    }
  });
});

