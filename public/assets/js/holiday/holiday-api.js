/**
 * Holiday API JavaScript Service
 * Provides functions to interact with holiday REST API
 */

class HolidayAPI {
  constructor() {
    this.baseUrl = "/api/holidays";
    this.cache = new Map();
  }

  /**
   * Get holidays for a specific year
   * @param {number} year - The year to get holidays for
   * @returns {Promise<Object>} Promise that resolves to holiday data
   */
  async getHolidaysByYear(year) {
    const cacheKey = `year_${year}`;

    if (this.cache.has(cacheKey)) {
      return this.cache.get(cacheKey);
    }

    try {
      const response = await fetch(`${this.baseUrl}/year/${year}`);
      const data = await response.json();

      if (data.success) {
        this.cache.set(cacheKey, data);
        return data;
      } else {
        throw new Error(data.message);
      }
    } catch (error) {
      console.error("Error fetching holidays for year:", error);
      throw error;
    }
  }

  /**
   * Check if a specific date is a holiday
   * @param {string} date - Date in YYYY-MM-DD format
   * @returns {Promise<Object>} Promise that resolves to holiday check result
   */
  async checkHoliday(date) {
    try {
      const response = await fetch(`${this.baseUrl}/check/${date}`);
      const data = await response.json();

      if (data.success) {
        return data;
      } else {
        throw new Error(data.message);
      }
    } catch (error) {
      console.error("Error checking holiday:", error);
      throw error;
    }
  }

  /**
   * Get holidays within a date range
   * @param {string} startDate - Start date in YYYY-MM-DD format
   * @param {string} endDate - End date in YYYY-MM-DD format
   * @returns {Promise<Object>} Promise that resolves to holidays in range
   */
  async getHolidaysInRange(startDate, endDate) {
    try {
      const response = await fetch(
        `${this.baseUrl}/range/${startDate}/${endDate}`
      );
      const data = await response.json();

      if (data.success) {
        return data;
      } else {
        throw new Error(data.message);
      }
    } catch (error) {
      console.error("Error fetching holidays in range:", error);
      throw error;
    }
  }

  /**
   * Get holidays for a specific month
   * @param {number} year - The year
   * @param {number} month - The month (1-12)
   * @returns {Promise<Object>} Promise that resolves to month holidays
   */
  async getHolidaysByMonth(year, month) {
    try {
      const response = await fetch(`${this.baseUrl}/month/${year}/${month}`);
      const data = await response.json();

      if (data.success) {
        return data;
      } else {
        throw new Error(data.message);
      }
    } catch (error) {
      console.error("Error fetching holidays by month:", error);
      throw error;
    }
  }

  /**
   * Get total number of holidays in a year
   * @param {number} year - The year
   * @returns {Promise<Object>} Promise that resolves to total count
   */
  async getTotalHolidays(year) {
    try {
      const response = await fetch(`${this.baseUrl}/total/${year}`);
      const data = await response.json();

      if (data.success) {
        return data;
      } else {
        throw new Error(data.message);
      }
    } catch (error) {
      console.error("Error getting total holidays:", error);
      throw error;
    }
  }

  /**
   * Get available years for holiday data
   * @returns {Promise<Object>} Promise that resolves to available years
   */
  async getAvailableYears() {
    try {
      const response = await fetch(`${this.baseUrl}/available-years`);
      const data = await response.json();

      if (data.success) {
        return data;
      } else {
        throw new Error(data.message);
      }
    } catch (error) {
      console.error("Error getting available years:", error);
      throw error;
    }
  }

  /**
   * Check if today is a holiday
   * @returns {Promise<Object>} Promise that resolves to today's holiday status
   */
  async isTodayHoliday() {
    const today = new Date().toISOString().split("T")[0];
    return await this.checkHoliday(today);
  }

  /**
   * Get next holiday from a given date
   * @param {string} fromDate - Start date in YYYY-MM-DD format
   * @returns {Promise<Object|null>} Promise that resolves to next holiday or null
   */
  async getNextHoliday(fromDate) {
    const year = new Date(fromDate).getFullYear();
    const holidays = await this.getHolidaysByYear(year);

    if (holidays.success) {
      const futureHolidays = holidays.data.filter(
        (holiday) => holiday.holiday_date > fromDate
      );

      if (futureHolidays.length > 0) {
        return futureHolidays.sort(
          (a, b) => new Date(a.holiday_date) - new Date(b.holiday_date)
        )[0];
      }
    }

    return null;
  }

  /**
   * Get holidays for current year
   * @returns {Promise<Object>} Promise that resolves to current year holidays
   */
  async getCurrentYearHolidays() {
    const currentYear = new Date().getFullYear();
    return await this.getHolidaysByYear(currentYear);
  }

  /**
   * Clear cache
   */
  clearCache() {
    this.cache.clear();
  }

  /**
   * Get cached data
   * @param {string} key - Cache key
   * @returns {Object|null} Cached data or null
   */
  getCached(key) {
    return this.cache.get(key) || null;
  }
}

// Create global instance
window.HolidayAPI = new HolidayAPI();

// Utility functions for easy access
window.HolidayUtils = {
  /**
   * Format date for API calls
   * @param {Date|string} date - Date to format
   * @returns {string} Formatted date string
   */
  formatDateForAPI(date) {
    if (typeof date === "string") {
      return date;
    }
    return date.toISOString().split("T")[0];
  },

  /**
   * Check if date is weekend
   * @param {Date|string} date - Date to check
   * @returns {boolean} True if weekend
   */
  isWeekend(date) {
    const d = new Date(date);
    return d.getDay() === 0 || d.getDay() === 6;
  },

  /**
   * Get business days between two dates (excluding weekends and holidays)
   * @param {string} startDate - Start date
   * @param {string} endDate - End date
   * @returns {Promise<number>} Number of business days
   */
  async getBusinessDays(startDate, endDate) {
    const holidays = await window.HolidayAPI.getHolidaysInRange(
      startDate,
      endDate
    );
    const holidayDates = holidays.data.map((h) => h.holiday_date);

    let businessDays = 0;
    const current = new Date(startDate);
    const end = new Date(endDate);

    while (current <= end) {
      const dateStr = current.toISOString().split("T")[0];
      const isWeekend = this.isWeekend(current);
      const isHoliday = holidayDates.includes(dateStr);

      if (!isWeekend && !isHoliday) {
        businessDays++;
      }

      current.setDate(current.getDate() + 1);
    }

    return businessDays;
  },
};

// Example usage:
/*
// Get holidays for 2025
HolidayAPI.getHolidaysByYear(2025).then(data => {
    console.log('2025 Holidays:', data);
});

// Check if today is a holiday
HolidayAPI.isTodayHoliday().then(result => {
    if (result.isHoliday) {
        console.log('Today is a holiday:', result.holidayInfo.holiday_name);
    } else {
        console.log('Today is not a holiday');
    }
});

// Get holidays for current month
const currentDate = new Date();
HolidayAPI.getHolidaysByMonth(currentDate.getFullYear(), currentDate.getMonth() + 1)
    .then(data => {
        console.log('Current month holidays:', data);
    });
*/
