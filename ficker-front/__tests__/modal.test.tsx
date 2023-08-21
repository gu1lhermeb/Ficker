import React from "react";
import "@testing-library/jest-dom";
import { OutputModal } from "@/app/Outputs/modal";
import { render } from "@testing-library/react";
import { screen } from "@testing-library/dom";
import selectEvent from "react-select-event";

// const setIsModalOpen = jest.fn();

describe("Modal", () => {
  beforeAll(() => {
    Object.defineProperty(window, "matchMedia", {
      writable: true,
      value: jest.fn().mockImplementation((query) => ({
        matches: false,
        media: query,
        onchange: null,
        addListener: jest.fn(), // Deprecated
        removeListener: jest.fn(), // Deprecated
        addEventListener: jest.fn(),
        removeEventListener: jest.fn(),
        dispatchEvent: jest.fn(),
      })),
    });
  });
  it("Fields should be required", () => {
    render(<OutputModal isModalOpen={true} setIsModalOpen={() => {}} />);
    const description = screen.getByTestId("description");
    const value = screen.getByTestId("value");
    const date = screen.getByTestId("date");
    expect(description).toBeRequired();
    expect(value).toBeRequired();
    expect(date).toBeRequired();
  });
});
