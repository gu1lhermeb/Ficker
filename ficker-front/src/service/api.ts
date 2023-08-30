import axios, { AxiosRequestConfig } from "axios";

interface RequestParams {
  method?: AxiosRequestConfig["method"];
  headers?: AxiosRequestConfig["headers"];
  endpoint: string;
  data?: AxiosRequestConfig["data"];
  params?: AxiosRequestConfig["params"];
  loaderStateSetter?: (state: boolean) => void;
}

const toggleLoader = (loaderStateSetter: ((state: boolean) => void) | undefined, state: boolean) => {
  if (loaderStateSetter) {
    loaderStateSetter(state);
  }
};

export const request = async ({
  method = "get",
  headers = {},
  endpoint,
  data,
  params,
  loaderStateSetter,
}: RequestParams) => {
  const baseUrl = "back.ficker.tech/api";
  const token = localStorage.getItem("token");
  const config: AxiosRequestConfig = {
    method,
    baseURL: `https://${baseUrl}/${endpoint}`,
    data,
    params,
    timeout: 7000,
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${token}`,
      ...headers,
    },
  };

  let result;
  toggleLoader(loaderStateSetter, true);
  try {
    result = await axios(config);
  } catch (error: any) {
    if (error.response.status === 401) {
      localStorage.removeItem("token");
      window.location.href = "/login";
    }
    throw error;
  }
  toggleLoader(loaderStateSetter, false);
  return result;
};
